<?php

namespace WordPress\Standard\Frontend;

class Shortcode
{
    public function __construct()
    {
        add_shortcode('coding-standard', [$this, 'render_shortcode']);
    }

    /**
     * rendering shortcode data to frontend
     *
     * @param [type] $atts
     * @return void
     */
    public function render_shortcode($atts)
    {
        return "It's working";
    }
}
