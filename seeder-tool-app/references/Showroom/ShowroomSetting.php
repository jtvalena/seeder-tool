<?php

declare(strict_types=1);

namespace App\Models\Showroom;

use Illuminate\Database\Eloquent\Model;

class ShowroomSetting extends Model
{
    protected $fillable = [
        'background_color_hex',
        'gradient_top_color_hex',
        'gradient_bottom_color_hex',
        'gradient_enabled',
        'background_image_path',
        'logo_image_path',
        'depot_customer_ids',
        'map_customer_ids',
        'map_pin_logos',
        'depot_logo_image_path',
        'logo_text',
        'logo_height_dp',
        'title_font_size_sp',
        'item_name_font_size_sp',
        'specs_font_size_sp',
        'srp_font_size_sp',
        'discount_font_size_sp',
        'final_price_font_size_sp',
        'mobile_logo_height_dp',
        'mobile_title_font_size_sp',
        'mobile_item_name_font_size_sp',
        'mobile_specs_font_size_sp',
        'mobile_srp_font_size_sp',
        'mobile_discount_font_size_sp',
        'mobile_final_price_font_size_sp',
        'tagline',
        'default_slide_duration_seconds',
        'watermark_enabled',
        'show_srp_price',
        'item_search_headline_1',
        'item_search_headline_2',
    ];

    protected $casts = [
        'default_slide_duration_seconds' => 'integer',
        'logo_height_dp' => 'integer',
        'title_font_size_sp' => 'integer',
        'item_name_font_size_sp' => 'integer',
        'specs_font_size_sp' => 'integer',
        'srp_font_size_sp' => 'integer',
        'discount_font_size_sp' => 'integer',
        'final_price_font_size_sp' => 'integer',
        'mobile_logo_height_dp' => 'integer',
        'mobile_title_font_size_sp' => 'integer',
        'mobile_item_name_font_size_sp' => 'integer',
        'mobile_specs_font_size_sp' => 'integer',
        'mobile_srp_font_size_sp' => 'integer',
        'mobile_discount_font_size_sp' => 'integer',
        'mobile_final_price_font_size_sp' => 'integer',
        'watermark_enabled' => 'boolean',
        'show_srp_price' => 'boolean',
        'gradient_enabled' => 'boolean',
        'depot_customer_ids' => 'array',
        'map_customer_ids' => 'array',
        'map_pin_logos' => 'array',
        'item_search_headline_1' => 'array',
        'item_search_headline_2' => 'array',
    ];
}
