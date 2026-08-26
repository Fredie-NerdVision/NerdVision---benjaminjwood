<?php
/**
 * Index Template: Benjamin J Wood Composer Portfolio
 * Built with Tailwind CSS and ACF
 */

get_header(); ?>

<main class="bg-stone-950 text-stone-100 min-h-screen font-sans">

    <!-- HERO SECTION -->
    <section class="relative h-[80vh] flex items-center justify-center overflow-hidden border-b border-stone-800">
        <?php 
        $hero_bg = get_field('hero_background_image');
        if ($hero_bg): ?>
            <img src="<?php echo esc_url($hero_bg['url']); ?>" alt="<?php echo esc_attr($hero_bg['alt']); ?>" class="absolute inset-0 w-full h-full object-cover opacity-30">
        <?php endif; ?>
        
        <div class="relative z-10 text-center px-4 max-w-4xl">
            <h1 class="text-5xl md:text-7xl font-light tracking-tighter mb-4 uppercase">
                <?php the_field('hero_headline'); ?>
            </h1>
            <p class="text-xl md:text-2xl text-stone-400 font-light max-w-2xl mx-auto">
                <?php the_field('hero_subheadline'); ?>
            </p>
        </div>
    </section>

    <!-- DUAL-AUDIO TANDEM PLAYER -->
    <section class="py-24 px-4 bg-stone-900">
        <div class="max-w-5xl mx-auto">
            <div class="mb-12 text-center">
                <h2 class="text-sm uppercase tracking-[0.3em] text-amber-500 mb-2"><?php the_field('tandem_section_label'); ?></h2>
                <h3 class="text-3xl font-light"><?php the_field('tandem_track_title'); ?></h3>
            </div>

            <div class="bg-stone-950 p-8 rounded-lg border border-stone-800 shadow-2xl">
                <div class="flex flex-col md:flex-row items-center gap-12">
                    
                    <!-- Player Controls -->
                    <div class="w-full md:w-1/3 flex flex-col items-center gap-6">
                        <button id="masterPlayBtn" class="w-20 h-20 bg-stone-100 text-stone-950 rounded-full flex items-center justify-center hover:bg-amber-500 transition-colors">
                            <svg id="playIcon" class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            <svg id="pauseIcon" class="w-8 h-8 hidden" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                        </button>
                        
                        <!-- Toggle Switch -->
                        <div class="flex flex-col items-center gap-3">
                            <span class="text-[10px] uppercase tracking-widest text-stone-500">Toggle Version</span>
                            <div class="flex items-center gap-4">
                                <span id="labelA" class="text-xs font-bold text-amber-500 transition-colors"><?php the_field('label_version_a'); ?></span>
                                <button id="versionToggle" class="w-14 h-7 bg-stone-800 rounded-full relative p-1 transition-colors">
                                    <div id="toggleKnob" class="w-5 h-5 bg-white rounded-full transition-transform transform translate-x-0"></div>
                                </button>
                                <span id="labelB" class="text-xs font-bold text-stone-600 transition-colors"><?php the_field('label_version_b'); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Track Progress / Visualizer Placeholder -->
                    <div class="w-full md:w-2/3">
                        <div class="h-2 w-full bg-stone-800 rounded-full overflow-hidden mb-4 cursor-pointer" id="progressBarContainer">
                            <div id="progressBar" class="h-full bg-amber-500 w-0"></div>
                        </div>
                        <div class="flex justify-between text-[10px] font-mono text-stone-500 uppercase tracking-widest">
                            <span id="currentTime">00:00</span>
                            <span id="duration">00:00</span>
                        </div>
                        <p class="mt-6 text-stone-400 text-sm leading-relaxed italic">
                            <?php the_field('tandem_description'); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <?php 
        $audio_a = get_field('audio_file_a');
        $audio_b = get_field('audio_file_b');
        ?>
        <audio id="audioA" src="<?php echo esc_url($audio_a['url']); ?>" preload="auto"></audio>
        <audio id="audioB" src="<?php echo esc_url($audio_b['url']); ?>" preload="auto" muted></audio>
    </section>

    <!-- PORTFOLIO GRID -->
    <section class="py-24 px-4 max-w-7xl mx-auto">
        <h2 class="text-sm uppercase tracking-[0.3em] text-stone-500 mb-12 border-b border-stone-800 pb-4">Selected Works</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
            <?php if( have_rows('portfolio_repeater') ): while( have_rows('portfolio_repeater') ): the_row(); 
                $image = get_sub_field('project_thumbnail');
                $audio = get_sub_field('project_audio');
            ?>
                <div class="group">
                    <div class="aspect-video bg-stone-900 mb-4 overflow-hidden relative">
                        <?php if($image): ?>
                            <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700">
                        <?php endif; ?>
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-stone-950/60">
                            <button class="px-6 py-2 border border-white text-white text-xs uppercase tracking-widest hover:bg-white hover:text-black transition-colors">Play Track</button>
                        </div>
                    </div>
                    <h4 class="text-lg font-medium"><?php the_sub_field('project_title'); ?></h4>
                    <p class="text-stone-500 text-sm"><?php the_sub_field('project_client_or_genre'); ?></p>
                </div>
            <?php endwhile; endif; ?>
        </div>
    </section>

    <!-- CONTACT -->
    <section class="py-24 px-4 bg-stone-950 border-t border-stone-900">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-4xl font-light mb-8"><?php the_field('contact_headline'); ?></h2>
            <a href="mailto:<?php the_field('contact_email'); ?>" class="text-2xl text-amber-500 hover:text-amber-400 transition-colors underline underline-offset-8 decoration-1">
                <?php the_field('contact_email'); ?>
            </a>
        </div>
    </section>

</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const audioA = document.getElementById('audioA');
    const audioB = document.getElementById('audioB');
    const masterPlayBtn = document.getElementById('masterPlayBtn');
    const playIcon = document.getElementById('playIcon');
    const pauseIcon = document.getElementById('pauseIcon');
    const versionToggle = document.getElementById('versionToggle');
    const toggleKnob = document.getElementById('toggleKnob');
    const progressBar = document.getElementById('progressBar');
    const progressBarContainer = document.getElementById('progressBarContainer');
    const currentTimeDisplay = document.getElementById('currentTime');
    const durationDisplay = document.getElementById('duration');
    const labelA = document.getElementById('labelA');
    const labelB = document.getElementById('labelB');

    let isPlaying = false;
    let currentVersion = 'A'; // A or B

    function formatTime(secs) {
        const mins = Math.floor(secs / 60);
        const s = Math.floor(secs % 60);
        return (mins < 10 ? '0' : '') + mins + ':' + (s < 10 ? '0' : '') + s;
    }

    masterPlayBtn.addEventListener('click', () => {
        if (isPlaying) {
            audioA.pause();
            audioB.pause();
            playIcon.classList.remove('hidden');
            pauseIcon.classList.add('hidden');
        } else {
            audioA.play();
            audioB.play();
            playIcon.classList.add('hidden');
            pauseIcon.classList.remove('hidden');
        }
        isPlaying = !isPlaying;
    });

    versionToggle.addEventListener('click', () => {
        if (currentVersion === 'A') {
            audioA.muted = true;
            audioB.muted = false;
            currentVersion = 'B';
            toggleKnob.classList.replace('translate-x-0', 'translate-x-7');
            labelA.classList.replace('text-amber-500', 'text-stone-600');
            labelB.classList.replace('text-stone-600', 'text-amber-500');
        } else {
            audioA.muted = false;
            audioB.muted = true;
            currentVersion = 'A';
            toggleKnob.classList.replace('translate-x-7', 'translate-x-0');
            labelA.classList.replace('text-stone-600', 'text-amber-500');
            labelB.classList.replace('text-amber-500', 'text-stone-600');
        }
    });

    audioA.ontimeupdate = () => {
        const pct = (audioA.currentTime / audioA.duration) * 100;
        progressBar.style.width = pct + '%';
        currentTimeDisplay.innerText = formatTime(audioA.currentTime);
        
        // Anti-drift sync
        if (Math.abs(audioA.currentTime - audioB.currentTime) > 0.1) {
            audioB.currentTime = audioA.currentTime;
        }
    };

    audioA.onloadedmetadata = () => {
        durationDisplay.innerText = formatTime(audioA.duration);
    };

    progressBarContainer.addEventListener('click', (e) => {
        const rect = progressBarContainer.getBoundingClientRect();
        const pos = (e.clientX - rect.left) / rect.width;
        audioA.currentTime = pos * audioA.duration;
        audioB.currentTime = pos * audioB.duration;
    });
});
</script>

<?php get_footer(); ?>