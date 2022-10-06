<?php

trait UtilsTrait {

  public function get_global_settings()
  {
    if (!isset($GLOBALS['elementor_global_settings'])) {
      $GLOBALS['elementor_global_settings'] = (new \Elementor\Core\Kits\Manager)->get_current_settings();
    }

    return $GLOBALS['elementor_global_settings'];
  }

  public $computed_settings = false;
  
  public function computed_settings()
  {
    if ($this->computed_settings) {
      return $this->computed_settings;
    }

    $global_settings = $this->get_global_settings();
    
    // Global colors
    $global_colors = call_user_func(function() use($global_settings) {
      $global_colors = [];

      if (isset($global_settings['system_colors']) AND is_array($global_settings['system_colors'])) {
        foreach($global_settings['system_colors'] as $color) {
          $color['_class'] = $color['_id'];
          $global_colors[ $color['_id'] ] = $color;
        }
      }

      if (isset($global_settings['custom_colors']) AND is_array($global_settings['custom_colors'])) {
        foreach($global_settings['custom_colors'] as $color) {
          $color['_class'] = $color['title'];
          $global_colors[ $color['_id'] ] = $color;
        }
      }

      return $global_colors;
    });


    $settings = json_decode(json_encode($this->get_settings()), true);

    if (isset($settings['__globals__']) AND is_array($settings['__globals__'])) {
      foreach($settings['__globals__'] as $key => $value) {
        if (! $value) continue;
        $value = str_replace('globals/colors?id=', '', $value);
        if (isset($global_colors[$value]) AND !empty($global_colors[$value])) {
          $settings[ $key ] = $global_colors[ $value ]['color'];
        }
      }
    }

    $setting_return = new stdClass;
    foreach($settings as $key => $value) {
      if ($key[0] == '_') continue;
      if ($key == 'animation_duration') continue;
      if (strpos($key, 'motion_fx_transform') === 0) continue;
      if (strpos($key, 'hide_') === 0) continue;
      $setting_return->{$key} = $value;
    }

    $setting_return->id = uniqid($this->get_name() . '-');
    $setting_return->is_edit = \Elementor\Plugin::$instance->editor->is_edit_mode();
    $setting_return->is_preview = \Elementor\Plugin::$instance->preview->is_preview_mode();

    $this->computed_settings = $setting_return;
    return $setting_return;
  }


  public function render_full() {
    echo 'render aa';
  }


  public function render_style() {
    return false;
  }

  
  protected function render() {
    $set = $this->computed_settings();

    echo "\n\t\t<!--";
    echo "\n\t\tElement: ". $this->get_title();
    echo "\n\t\telementor/elements/". $this->get_name() . '.php';
    echo "\n\t\t-->";

    if ($style = $this->render_style()) {
      $style = str_replace('--id', "#{$set->id}", $style);
      echo "\n\t\t<style>{$style}</style>";
    }

    echo "\n";
    $this->render_full();
    echo "\n\t\t<!-- ". $this->get_name() . " end -->\n\n";
  }

}