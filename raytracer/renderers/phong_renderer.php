<?php
/**
 * Copyright 2011, Alok Menghrajani. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification, are
 * permitted provided that the following conditions are met:
 *
 * 1. Redistributions of source code must retain the above copyright notice, this list of
 *    conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright notice, this list
 *    of conditions and the following disclaimer in the documentation and/or other materials
 *    provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR IMPLIED
 * WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND
 * FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE AUTHOR OR
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 * NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF
 * ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * The views and conclusions contained in the software and documentation are those of the
 * authors and should not be interpreted as representing official policies, either expressed
 * or implied, of the author.
 */

/**
 * Phong (specular) rendering involves looking at the angle between the ray and the light.
 * This makes objects look shiny.
 */

class PhongRenderer extends Renderer {
  function render(World $world, Encoder $img, $width, $height) {
    $camera = $world->getCamera();

    $camera_z = clone $camera->getDirection();
    $camera_z->K_mul($width / 2 / tan($camera->getAngle()));

    // Cast rays, ($i, $j) is screen coordinates
    for ($j = 0; $j < $height; $j++) {
      for ($i = 0; $i < $width; $i++) {
        // Rays start at <camera> and go to
        // (d * <direction>) + (i - width/2) * <right>) + (+height/2 - j) * up
        $r = clone $camera_z;

        $t = clone $camera->getRight();
        $t->K_mul($i - $width / 2);
        $r->V_add($t);

        $t = clone $camera->getUp();
        $t->K_mul($height / 2 - $j);
        $r->V_add($t);

        $ray = new Ray();
        $ray->setOrigin($camera->getPosition());
        $ray->setDirection($r);

        // Calculate which object this ray touches
        $distance = null;
        $color = Color::$black;
        foreach ($world->getObjects() as $obj) {
          $r = $obj->intersect($ray, true, true);
          if ($r === null) {
            continue;
          }
          if (($distance === null) || ($r['d'] < $distance)) {
            $distance = $r['d'];
            // Cast a ray from $r['p'] to the light sources
            $new_ray = new Ray();
            $new_ray->setOrigin($r['p']);

            $hits_light = false;
            foreach ($world->getLights() as $light) {
              $new_ray->setDirection(Vector::fromAtoB(
                $new_ray->getOrigin(),
                $light->getPosition()));

              // Check if this ray hits anything
              $hits_light = true;
              foreach ($world->getObjects() as $obj2) {
                if ($obj2 === $obj) {
                  continue;
                }
                if ($obj2->intersect($new_ray, false, false) !== null) {
                  $hits_light = false;
                  break;
                }
              }
              if ($hits_light) {
                break;
              }
            }

            if ($hits_light) {
              $diffuse_shading = max($new_ray->getDirection()->V_dot($r['n']), 0);

              $reflected_ray = Vector::reflectedRay(
                $ray->getDirection(),
                $r['n']);

              $specular_shading = max($new_ray->getDirection()->V_dot($r['n']), 0);
              $specular_shading = pow($specular_shading, 16);

              $c = clone $obj->getColor();
              $color = $c->K_mul(min(0.7 * $diffuse_shading + 0.3 * $specular_shading, 1));
            } else {
              $color = Color::$black;
            }
          }
        }
        $img->setPixel($i, $j, $color);
      }
    }
  }
}
