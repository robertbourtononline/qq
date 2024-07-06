<?php

namespace ColorSamples;


class ColorSamples {

    private readonly array $color_array;
    private string $link_class;

    public function __construct( array $color_array, string $link_class) {
        $this->color_array = $color_array;
        $this->link_class = $link_class;
    }

    public function display_colors() {
        $keys = array_keys($this->color_array);
        
        echo "<div class='container mb-5'>";
        for ($i = 0; $i < count($keys); $i+= 2) {
            $this_value = $this->color_array[$keys[$i]][0];
            $this_color_code = $this->color_array[$keys[$i]][1];
            if (array_key_exists($keys[$i+1], $this->color_array)) {
                $next_value = $this->color_array[$keys[$i+1]][0];
                $next_color_code = $this->color_array[$keys[$i+1]][1];
            }
            
            $this_style = '';
            $next_style = '';
            
            if (str_starts_with($this_value, '#')) {
                $this_style = "background: " . $this_value;
            } else {
                $this_style = "background-image: url({$this_value});background-size: contain;background-repeat:no-repeat;";
            }
    
            if (str_starts_with($next_value, '#')) {
                $next_style = "background: " . $next_value;
            } else {
                $next_style = "background-image: url({$next_value});background-size: contain;;background-repeat:no-repeat;"; ;
            }
    
            echo "
                <div class='row'>
                    <div class='col-md-2 mt-3'>
                        <h6> <a class='{$this->link_class}' data-name='{$keys[$i]}' data-code='{$this_color_code}' href='#'>{$keys[$i]}</a> {$this_color_code}</h6>
                        <a  href='#'>
                            <div class='p-5 {$this->link_class}' data-name='{$keys[$i]}' data-code='{$this_color_code}' style='{$this_style}'></div>
                        </a>
                    </div>
                    
                    <div class='col-md-2 offset-md-2 mt-3'>
                        <h6> <a class='{$this->link_class}' data-name='{$keys[$i+1]}' data-code='{$next_color_code}' href='#'>{$keys[$i+1]}</a> {$next_color_code}</h6>
                        <a  href='#'>
                            <div class='p-5 {$this->link_class}' data-name='{$keys[$i+1]}' data-code='{$next_color_code}' style='{$next_style}'></div>
                        </a>
                    </div>
                </div>
            ";
        }
        echo "</div>";
    }
}




?>

</body>
</html>