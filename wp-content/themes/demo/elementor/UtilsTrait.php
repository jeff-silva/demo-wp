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
    $setting_return = $this->settings($setting_return);

    $this->computed_settings = $setting_return;
    return $setting_return;
  }


  public function settings($set) {
    return $set;
  }


  public function content() {
    echo 'render aa';
  }


  public function style() {
    return false;
  }
  
  
  public function footer() {
    return false;
  }

  
  protected function render() {
    $self = $this;
    $set = $this->computed_settings();
    
    $comment_start = implode("\n", [
      "\t\t<!--",
      "\t\tElement: ". $this->get_title(),
      "\t\telementor/elements/". $this->get_name() . '.php',
      "\t\t-->",
    ]);
    
    $comment_final = implode("\n", [
      "\n\t\t<!-- ". $this->get_name() . " end -->\n\n",
    ]);

    echo $comment_start;
    $this->content($set);
    echo $comment_final;

    $render_script_style = function() use($self, $set, $comment_start, $comment_final) {
      echo $comment_start;
      if ($style = $self->style($set)) {
        $style = str_replace('--id', "#{$set->id}", $style);
        echo "\n\t\t<style>{$style}</style>";
      }

      $self->footer($set);
      echo $comment_final;
    };

    if ($set->is_edit) {
      $render_script_style();
    }
    else {
      add_action('get_footer', function() use($render_script_style) {
        $render_script_style();
      });
    }
  }

}