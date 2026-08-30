/**
 * Tandem A/B player.
 *
 * Plays two renders of the same piece in perfect lockstep, but only ever lets
 * one of them be heard: switching lanes swaps the audible version instantly at
 * the same point in the timeline, so the treatment is the only thing that
 * changes. Both elements keep running, which is what makes the switch gapless.
 *
 * Markup contract (data attributes):
 *   [data-tandem]                 root element
 *   [data-tandem-track="<i>"]     track selector button
 *   [data-lane="a"|"b"]           lane wrapper
 *   the lane wrapper itself is the audition control: clicking it makes that
 *   version the audible one
 *   [data-lane-gain] [data-lane-name]
 *   [data-lane-meter]             container that receives meter bars
 *   [data-ab-switch="a"|"b"]      audition switch in the listening room
 *   [data-player-play] [data-player-prev] [data-player-next]
 *   [data-player-title] [data-player-artist]
 *   [data-player-current] [data-player-duration]
 *   [data-player-scrub] [data-player-fill]
 *   [data-player-ab="a"|"b"]      audition switch in the sticky bar
 *   [data-player-volume]          master output level
 */
(function () {
    'use strict';

    var SYNC_TOLERANCE = 0.08;
    var METER_BARS = 18;

    /**
     * Media from another origin taints the element: the Web Audio graph would
     * output silence, and requesting CORS outright fails on hosts that send no
     * Access-Control-Allow-Origin. Such sources fall back to element volume.
     */
    function isSameOrigin(src) {
        try {
            return new URL(src, window.location.href).origin === window.location.origin;
        } catch (err) {
            return false;
        }
    }

    function fmt(seconds) {
        if (!isFinite(seconds) || seconds < 0) { return '0:00'; }
        var m = Math.floor(seconds / 60);
        var s = Math.floor(seconds % 60);
        return m + ':' + (s < 10 ? '0' : '') + s;
    }

    function TandemPlayer(root, tracks) {
        this.root = root;
        this.tracks = tracks;
        this.index = 0;
        this.playing = false;
        this.active = 'a';
        this.master = 1;
        this.lanes = {};
        this.ctx = null;
        this.graphSafe = tracks.every(function (track) {
            return isSameOrigin(track.a.src) && isSameOrigin(track.b.src);
        });

        this.el = {
            play: document.querySelector('[data-player-play]'),
            prev: document.querySelector('[data-player-prev]'),
            next: document.querySelector('[data-player-next]'),
            title: document.querySelector('[data-player-title]'),
            artist: document.querySelector('[data-player-artist]'),
            current: document.querySelector('[data-player-current]'),
            duration: document.querySelector('[data-player-duration]'),
            scrub: document.querySelector('[data-player-scrub]'),
            fill: document.querySelector('[data-player-fill]'),
            volume: document.querySelector('[data-player-volume]')
        };

        this.setupLanes();
        this.setupControls();
        this.load(0);
        this.tick();
    }

    TandemPlayer.prototype.setupLanes = function () {
        var self = this;

        ['a', 'b'].forEach(function (key) {
            var wrap = self.root.querySelector('[data-lane="' + key + '"]');
            var audio = new Audio();
            audio.preload = 'metadata';
            if (self.graphSafe) { audio.crossOrigin = 'anonymous'; }

            var lane = {
                key: key,
                wrap: wrap,
                audio: audio,
                gain: 1,
                meterBars: [],
                analyser: null,
                gainNode: null,
                data: null
            };

            if (wrap) {
                var meter = wrap.querySelector('[data-lane-meter]');
                if (meter) {
                    meter.innerHTML = '';
                    for (var i = 0; i < METER_BARS; i++) {
                        var bar = document.createElement('span');
                        bar.style.height = '2px';
                        meter.appendChild(bar);
                        lane.meterBars.push(bar);
                    }
                }

                // The whole lane is the switch; only its own controls opt out.
                wrap.addEventListener('click', function (event) {
                    if (event.target.closest('input, label')) { return; }
                    self.setActive(key);
                });

                wrap.addEventListener('keydown', function (event) {
                    if ('Enter' !== event.key && ' ' !== event.key) { return; }
                    if (event.target !== wrap) { return; }
                    event.preventDefault();
                    self.setActive(key);
                });

                var gain = wrap.querySelector('[data-lane-gain]');
                if (gain) {
                    gain.addEventListener('input', function () {
                        lane.gain = Number(gain.value) / 100;
                        self.applyMix();
                    });
                }
            }

            self.lanes[key] = lane;
        });

        if (this.el.volume) {
            this.master = Number(this.el.volume.value) / 100;
            this.el.volume.addEventListener('input', function () {
                self.master = Number(self.el.volume.value) / 100;
                self.applyMix();
            });
        }

        Array.prototype.forEach.call(
            document.querySelectorAll('[data-player-ab], [data-ab-switch]'),
            function (btn) {
                var key = btn.getAttribute('data-player-ab') || btn.getAttribute('data-ab-switch');
                btn.addEventListener('click', function () { self.setActive(key); });
            }
        );

        this.lanes.a.audio.addEventListener('loadedmetadata', function () {
            if (self.el.duration) { self.el.duration.textContent = fmt(self.lanes.a.audio.duration); }
        });

        this.lanes.a.audio.addEventListener('ended', function () { self.next(); });
    };

    TandemPlayer.prototype.setupControls = function () {
        var self = this;

        if (this.el.play) {
            this.el.play.addEventListener('click', function () { self.toggle(); });
        }
        if (this.el.prev) {
            this.el.prev.addEventListener('click', function () { self.prev(); });
        }
        if (this.el.next) {
            this.el.next.addEventListener('click', function () { self.next(); });
        }
        if (this.el.scrub) {
            this.el.scrub.addEventListener('click', function (event) {
                var rect = self.el.scrub.getBoundingClientRect();
                var ratio = Math.min(1, Math.max(0, (event.clientX - rect.left) / rect.width));
                var duration = self.lanes.a.audio.duration;
                if (isFinite(duration)) { self.seek(duration * ratio); }
            });
        }

        Array.prototype.forEach.call(this.root.querySelectorAll('[data-tandem-track]'), function (btn) {
            btn.addEventListener('click', function () {
                var i = Number(btn.getAttribute('data-tandem-track'));
                var wasPlaying = self.playing;
                self.load(i);
                if (wasPlaying) { self.play(); }
            });
        });
    };

    TandemPlayer.prototype.load = function (index) {
        var track = this.tracks[index];
        if (!track) { return; }

        this.pause();
        this.index = index;

        this.lanes.a.data = track.a;
        this.lanes.b.data = track.b;

        ['a', 'b'].forEach(function (key) {
            var lane = this.lanes[key];
            lane.audio.src = lane.data.src;
            lane.audio.load();
            var name = lane.wrap && lane.wrap.querySelector('[data-lane-name]');
            if (name) { name.textContent = lane.data.label; }

            Array.prototype.forEach.call(document.querySelectorAll('[data-ab-switch="' + key + '"]'), function (btn) {
                btn.textContent = lane.data.label;
            });
        }, this);

        if (this.el.title) { this.el.title.textContent = track.title; }
        if (this.el.artist) { this.el.artist.textContent = track.artist; }
        if (this.el.current) { this.el.current.textContent = '0:00'; }
        if (this.el.duration) { this.el.duration.textContent = '--:--'; }
        if (this.el.fill) { this.el.fill.style.width = '0%'; }

        Array.prototype.forEach.call(this.root.querySelectorAll('[data-tandem-track]'), function (btn) {
            btn.classList.toggle('is-active', Number(btn.getAttribute('data-tandem-track')) === index);
        });

        this.applyMix();
    };

    /* ----------------------------------------------------------- transport */

    TandemPlayer.prototype.toggle = function () {
        if (this.playing) { this.pause(); } else { this.play(); }
    };

    TandemPlayer.prototype.play = function () {
        var self = this;
        this.initAudioGraph();
        if (this.ctx && this.ctx.state === 'suspended') { this.ctx.resume(); }

        this.lanes.b.audio.currentTime = this.lanes.a.audio.currentTime;

        Promise.all([this.lanes.a.audio.play(), this.lanes.b.audio.play()])
            .then(function () { self.setPlayingState(true); })
            .catch(function () { self.setPlayingState(!self.lanes.a.audio.paused); });
    };

    TandemPlayer.prototype.pause = function () {
        this.lanes.a.audio.pause();
        this.lanes.b.audio.pause();
        this.setPlayingState(false);
    };

    TandemPlayer.prototype.setPlayingState = function (state) {
        this.playing = state;
        if (this.el.play) {
            this.el.play.classList.toggle('is-playing', state);
            this.el.play.setAttribute('aria-label', state ? 'Pause' : 'Play');
            var playIcon = this.el.play.querySelector('[data-icon="play"]');
            var pauseIcon = this.el.play.querySelector('[data-icon="pause"]');
            if (playIcon) { playIcon.style.display = state ? 'none' : ''; }
            if (pauseIcon) { pauseIcon.style.display = state ? '' : 'none'; }
        }
        document.body.classList.toggle('is-audio-playing', state);
    };

    TandemPlayer.prototype.seek = function (time) {
        this.lanes.a.audio.currentTime = time;
        this.lanes.b.audio.currentTime = time;
    };

    TandemPlayer.prototype.next = function () {
        var wasPlaying = this.playing;
        this.load((this.index + 1) % this.tracks.length);
        if (wasPlaying) { this.play(); }
    };

    TandemPlayer.prototype.prev = function () {
        var wasPlaying = this.playing;
        if (this.lanes.a.audio.currentTime > 3) {
            this.seek(0);
            return;
        }
        this.load((this.index - 1 + this.tracks.length) % this.tracks.length);
        if (wasPlaying) { this.play(); }
    };

    /* ---------------------------------------------------------------- mix */

    /**
     * Audition a lane. The other one keeps playing silently so switching back
     * lands on the same instant of the piece.
     */
    TandemPlayer.prototype.setActive = function (key) {
        if (!this.lanes[key]) { return; }
        this.active = key;
        this.applyMix();
    };

    TandemPlayer.prototype.applyMix = function () {
        ['a', 'b'].forEach(function (key) {
            var lane = this.lanes[key];
            var audible = key === this.active;
            var level = audible ? lane.gain * this.master : 0;

            if (lane.gainNode) {
                lane.gainNode.gain.value = level;
                lane.audio.volume = 1;
            } else {
                lane.audio.volume = level;
            }

            // Belt and braces: a muted element is silent even if the Web Audio
            // graph failed to build, so two versions can never overlap.
            lane.audio.muted = !audible;

            if (lane.wrap) {
                lane.wrap.classList.toggle('is-muted', !audible);
                lane.wrap.classList.toggle('is-solo', audible);
                lane.wrap.setAttribute('aria-pressed', String(audible));
            }

            Array.prototype.forEach.call(
                document.querySelectorAll('[data-player-ab="' + key + '"], [data-ab-switch="' + key + '"]'),
                function (btn) {
                    btn.classList.toggle('is-on', audible);
                    btn.setAttribute('aria-pressed', String(audible));
                }
            );
        }, this);
    };

    /* -------------------------------------------------------- audio graph */

    TandemPlayer.prototype.initAudioGraph = function () {
        if (this.ctx || !this.graphSafe) { return; }
        var Ctx = window.AudioContext || window.webkitAudioContext;
        if (!Ctx) { return; }

        try {
            this.ctx = new Ctx();
            ['a', 'b'].forEach(function (key) {
                var lane = this.lanes[key];
                var source = this.ctx.createMediaElementSource(lane.audio);
                lane.gainNode = this.ctx.createGain();
                lane.analyser = this.ctx.createAnalyser();
                lane.analyser.fftSize = 128;
                lane.analyser.smoothingTimeConstant = 0.75;
                source.connect(lane.gainNode);
                lane.gainNode.connect(lane.analyser);
                lane.analyser.connect(this.ctx.destination);
            }, this);
            this.applyMix();
        } catch (err) {
            this.ctx = null;
        }
    };

    /* --------------------------------------------------------------- loop */

    TandemPlayer.prototype.tick = function () {
        var self = this;

        function frame() {
            self.updateProgress();
            self.updateMeters();
            self.keepInSync();
            window.requestAnimationFrame(frame);
        }

        window.requestAnimationFrame(frame);
    };

    TandemPlayer.prototype.keepInSync = function () {
        if (!this.playing) { return; }
        var a = this.lanes.a.audio;
        var b = this.lanes.b.audio;
        if (Math.abs(a.currentTime - b.currentTime) > SYNC_TOLERANCE) {
            b.currentTime = a.currentTime;
        }
    };

    TandemPlayer.prototype.updateProgress = function () {
        var audio = this.lanes.a.audio;
        var duration = audio.duration;
        if (this.el.current) { this.el.current.textContent = fmt(audio.currentTime); }
        if (this.el.fill && isFinite(duration) && duration > 0) {
            this.el.fill.style.width = ((audio.currentTime / duration) * 100) + '%';
        }
    };

    TandemPlayer.prototype.updateMeters = function () {
        ['a', 'b'].forEach(function (key) {
            var lane = this.lanes[key];
            if (!lane.meterBars.length) { return; }

            // The analyser is wired after the gain node, so its readings already
            // carry the lane level; only the synthetic fallback needs scaling.
            var audible = key === this.active;
            var level = lane.analyser ? 1 : (audible ? lane.gain * this.master : 0);
            var values;

            if (lane.analyser) {
                var data = new Uint8Array(lane.analyser.frequencyBinCount);
                lane.analyser.getByteFrequencyData(data);
                values = lane.meterBars.map(function (bar, i) {
                    var slot = Math.floor((i / lane.meterBars.length) * data.length * 0.7);
                    return data[slot] / 255;
                });
            } else {
                // No analyser (blocked context or cross-origin audio): synthesise
                // a gentle motion so the meters still read as "signal present".
                var t = this.playing ? performance.now() / 240 : 0;
                values = lane.meterBars.map(function (bar, i) {
                    if (!t) { return 0.02; }
                    return (Math.sin(t + i * 0.6) * 0.3 + 0.45) * (0.7 + 0.3 * Math.sin(t * 0.5 + i));
                });
            }

            lane.meterBars.forEach(function (bar, i) {
                var height = Math.max(2, values[i] * level * 44);
                bar.style.height = height.toFixed(1) + 'px';
            });
        }, this);
    };

    document.addEventListener('DOMContentLoaded', function () {
        var root = document.querySelector('[data-tandem]');
        var tracks = window.BJW_TRACKS;
        if (!root || !tracks || !tracks.length) { return; }
        window.bjwPlayer = new TandemPlayer(root, tracks);
    });
}());
