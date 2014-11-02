<?php
    $files = dirToArray(dirname(__FILE__));
    $total = 0;

    foreach ($files as $key => $value)
    {
        if (is_array($value) && count($value) > 0)
        {
            $total += count($value);
        }
    }

    function dirToArray($dir) { 

        $ignore = array('.', '..', 'assets', 'js');
        $result = array(); 
        $root = scandir($dir); 
        $dirs = array_diff($root, $ignore);

        foreach ($dirs as $key => $value) 
        { 
            if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) 
            { 
                $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value); 
            } 
            else 
            {
                if (substr($value, -3) === '.js' && $value !== 'deps.js')
                {
                    $result[] = $value; 
                }
            } 
        } 

        return $result; 
    } 

    function buildList($section) {

        global $files;

        $output = "";

        if ($section)
        {
            $tempFiles = $files[$section];
        }
        else
        {
            $tempFiles = $files;
        }

        foreach ($tempFiles as $key => $value)
        {
            if (is_array($value)) 
            {
                $output .= "<div class=\"section\">";
                $output .= "<h2>$key</h2>";
                $output .= buildList($key);
                $output .= "</div>";
            } 
            else 
            {
                $value2 = substr($value, 0, -3);
                $file = urlencode($value);
                $output .= "<div class=\"item\"><a href=\"index.php?f=$file\">$value2</a></div>";
            } 
        }

        return $output;

    }
?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Phaser Time Tests</title>
        <?php
            $libs = false;

            if (($_SERVER['SERVER_NAME'] === '192.168.1.84' || $_SERVER['SERVER_NAME'] === 'localhost') && $libs === false)
            {
                $p2 = false;
                $path = '../../phaser-dev';
                require('../../phaser-dev/build/config.php');
            }
            else
            {
                echo "<script src=\"js/box2d-html5.js\" type=\"text/javascript\"></script>";
                echo "<script src=\"js/phaser-arcade-physics.js\" type=\"text/javascript\"></script>";
                echo "<script src=\"js/box2d-plugin.js\" type=\"text/javascript\"></script>";
            }
        ?>
        <style>
            body {
                font-family: Arial;
                font-size: 14px;
            }

            a {
                color: #0000ff;
                text-decoration: none;
            }

            a:Hover {
                color: #ff0000;
                text-decoration: underline;
            }

            input {
                font-size: 18px;
            }

            h2 {
                padding: 0;
                margin: 8px 0px;
            }

            .section {
                padding: 16px;
                clear: both;
            }

            .section .item {
                float: left;
                padding: 8px;
            }
        </style>
    </head>
    <body>

        <div id="phaser-example"></div>

        <div class="section">
        <h2>Phaser Time Tests</h2>
        <?php
            echo buildList(false);
        ?>
        </div>

        <script type="text/javascript">
            
            <?php
                if (isset($_GET['f']))
                {
                    $src = file_get_contents($_GET['f']);
                    echo $src;
                }
            ?>

        </script>

    </body>
</html>