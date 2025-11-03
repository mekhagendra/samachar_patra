<?php
/**
 * Breaking News Component
 * Displays urgent/breaking news ticker or carousel
 * 
 * @package Samachar_Patra
 * @since 1.0
 * @version 2.0.0
 */

// Don't load directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<section class="breaking-news-section">
    <div class="container">
        <div class="breaking-news-ticker">
            <?php
            // Get breaking news posts (posts with 'breaking' tag or category)
            $breaking_posts = get_posts(array(
                'numberposts' => 5,
                'meta_query' => array(
                    'relation' => 'OR',
                    array(
                        'key' => '_breaking_news',
                        'value' => 'yes',
                        'compare' => '='
                    ),
                    array(
                        'key' => '_urgent_post',
                        'value' => 'yes',
                        'compare' => '='
                    )
                ),
                'orderby' => 'date',
                'order' => 'DESC'
            ));
            
            // If no breaking posts found, get latest posts from last 2 hours
            if (empty($breaking_posts)) {
                $breaking_posts = get_posts(array(
                    'numberposts' => 3,
                    'date_query' => array(
                        array(
                            'after' => '2 hours ago'
                        ),
                    ),
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));
            }
            
            if (!empty($breaking_posts)) : ?>
                <div class="breaking-ticker-wrapper">
                    <div class="breaking-label">
                        <i class="fas fa-bolt"></i>
                        <span>ब्रेकिङ न्युज</span>
                    </div>
                    
                    <div class="breaking-ticker">
                        <div class="ticker-content">
                            <?php foreach ($breaking_posts as $post) : 
                                setup_postdata($post);
                            ?>
                                <div class="ticker-item">
                                    <a href="<?php the_permalink(); ?>" class="ticker-link">
                                        <span class="ticker-time">
                                            <i class="fas fa-clock"></i>
                                            <?php echo get_the_time('g:i A'); ?>
                                        </span>
                                        <span class="ticker-title">
                                            <?php echo wp_trim_words(get_the_title(), 12, '...'); ?>
                                        </span>
                                    </a>
                                </div>
                            <?php endforeach; 
                            wp_reset_postdata(); ?>
                        </div>
                    </div>
                    
                    <div class="breaking-controls">
                        <button class="ticker-prev" aria-label="Previous News">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="ticker-next" aria-label="Next News">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
                
            <?php else : ?>
                <div class="no-breaking-news">
                    <div class="breaking-label">
                        <i class="fas fa-info-circle"></i>
                        <span>समाचार</span>
                    </div>
                    <div class="breaking-ticker">
                        <div class="ticker-content">
                            <div class="ticker-item">
                                <span class="ticker-title">हाल कुनै ब्रेकिङ न्युज छैन।</span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Breaking News Styles -->
<style>
.breaking-news-section {
    background: linear-gradient(45deg, #dc2626, #ef4444);
    color: white;
    padding: 10px 0;
    box-shadow: 0 2px 10px rgba(220, 38, 38, 0.3);
    position: relative;
    z-index: 100;
}

.breaking-ticker-wrapper {
    display: flex;
    align-items: center;
    gap: 15px;
}

.breaking-label {
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(0, 0, 0, 0.2);
    padding: 8px 15px;
    border-radius: 25px;
    font-weight: 700;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
    backdrop-filter: blur(10px);
}

.breaking-label i {
    font-size: 16px;
    animation: flash 1.5s infinite;
}

@keyframes flash {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
}

.breaking-ticker {
    flex: 1;
    overflow: hidden;
    height: 40px;
    position: relative;
}

.ticker-content {
    display: flex;
    animation: scroll-horizontal 30s linear infinite;
    white-space: nowrap;
}

@keyframes scroll-horizontal {
    0% { transform: translateX(100%); }
    100% { transform: translateX(-100%); }
}

.ticker-item {
    display: inline-flex;
    align-items: center;
    margin-right: 50px;
    white-space: nowrap;
}

.ticker-link {
    color: white;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s ease;
}

.ticker-link:hover {
    color: #fbbf24;
    text-shadow: 0 0 10px rgba(251, 191, 36, 0.5);
}

.ticker-time {
    background: rgba(0, 0, 0, 0.3);
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.ticker-title {
    font-weight: 600;
    font-size: 14px;
}

.breaking-controls {
    display: flex;
    gap: 5px;
}

.ticker-prev,
.ticker-next {
    background: rgba(0, 0, 0, 0.2);
    border: none;
    color: white;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.ticker-prev:hover,
.ticker-next:hover {
    background: rgba(0, 0, 0, 0.4);
    transform: scale(1.1);
}

.no-breaking-news {
    display: flex;
    align-items: center;
    gap: 15px;
    opacity: 0.8;
}

/* Responsive Design */
@media (max-width: 768px) {
    .breaking-ticker-wrapper {
        gap: 10px;
    }
    
    .breaking-label {
        padding: 6px 10px;
        font-size: 12px;
    }
    
    .ticker-title {
        font-size: 13px;
    }
    
    .breaking-controls {
        display: none;
    }
    
    .ticker-content {
        animation-duration: 25s;
    }
}

@media (max-width: 480px) {
    .breaking-label span {
        display: none;
    }
    
    .breaking-label {
        padding: 6px 8px;
    }
    
    .ticker-time {
        display: none;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tickerContent = document.querySelector('.ticker-content');
    const prevBtn = document.querySelector('.ticker-prev');
    const nextBtn = document.querySelector('.ticker-next');
    
    if (prevBtn && nextBtn && tickerContent) {
        let isPaused = false;
        
        // Pause animation on hover
        tickerContent.addEventListener('mouseenter', function() {
            this.style.animationPlayState = 'paused';
            isPaused = true;
        });
        
        tickerContent.addEventListener('mouseleave', function() {
            if (!isPaused) {
                this.style.animationPlayState = 'running';
            }
        });
        
        // Manual controls (basic implementation)
        prevBtn.addEventListener('click', function() {
            // Implement manual previous logic if needed
        });
        
        nextBtn.addEventListener('click', function() {
            // Implement manual next logic if needed
        });
    }
});
</script>