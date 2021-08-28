<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logo</title>
</head>
<body>
    <?php
        $H = 108;
        $left = 11;
        $h1 = 34;
        $r1 = 2;
        $w1 = 24;
        $w2 = 24;
        $r2 = 2;
        $root2 = 1.41421356237;
        $tan_half45 = 0.41421356237;
        
        $Bx = $left + $r1;
        $By = ($H - $h1)/2;
        $Ax = $left;
        $Ay = $By + $r1;
        $Cx = $Ax + $w1;
        $Cy = $By;
        $Ex = $Ax + $w1 + $w2;
        $Ey = $Cy - $w2 + ($r2/$tan_half45);
        $Dx = $Ex - $r2 - ($r2 / $root2);
        $Dy = $Ey - ($r2 / $root2);
        $Fx = $Ex;
        $Fy = $H - $Ey;
        $Gx = $Dx;
        $Gy = $H - $Dy;
        $Jx = $Cx;
        $Jy = $H - $Cy;
        $Kx = $Bx;
        $Ky = $H - $By;
        $Mx = $Ax;
        $My = $H - $Ay;
        
        // $sinX1 = $H - 24;
        $sinX1 = 16;
        $sinY1 = $Ey;
        $loops = 5;
        $amplitude = 14;
        $loopY = ($H - (2 * $sinY1)) / $loops;
        
        $bg = '#292524';
        $prim = '#4db6ac';
    ?>
    <div width="100" height="100" style="display:inline-block;border-radius:14px;overflow:hidden;">
        <svg style="display:block;" width="100" height="100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 <?=$H;?> <?=$H;?>">
            <!-- <path fill="<?=$bg;?>" d="M0,0 L108,0L108,108L0,108z" /> -->
            <!-- <path fill="<?=$prim;?>" d="
                M <?=$Ax;?>,<?=$Ay;?>
                A <?=$r1;?> <?=$r1;?> 0 0 1 <?=$Bx;?> <?=$By;?>
                L <?=$Cx;?> <?=$Cy;?>
                L <?=$Dx;?> <?=$Dy;?>
                A <?=$r2;?> <?=$r2;?> 0 0 1 <?=$Ex;?> <?=$Ey;?>
                L <?=$Fx;?> <?=$Fy;?>
                A <?=$r2;?> <?=$r2;?> 0 0 1 <?=$Gx;?> <?=$Gy;?>
                L <?=$Jx;?> <?=$Jy;?>
                L <?=$Kx;?> <?=$Ky;?>
                A <?=$r1;?> <?=$r1;?> 0 0 1 <?=$Mx;?> <?=$My;?>
                z" /> -->
            <path stroke="<?=$prim;?>" stroke-width="4" stroke-linecap="round" fill="transparent" d="
                M <?=$sinX1;?> <?=$sinY1;?>
                <?php
                $y = $sinY1;
                for ($i=0; $i<$loops; ++$i) {
                    $oddMult = (-2 * ($i % 2)) + 1;
                    $x1 = $sinX1 + ($oddMult * $amplitude);
                    $y1 = $y + ($loopY * 0.3642);
                    $y2 = $y + ($loopY * 0.6358);
                    $yFinal = $y + $loopY;
                    echo "C $x1 $y1, $x1 $y2, $sinX1 $yFinal".PHP_EOL;
                    $y = $yFinal;
                }
                ?>
                " />
        </svg>
    </div>
</body>
</html>