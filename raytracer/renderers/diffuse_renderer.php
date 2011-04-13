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
 * Diffuse rendering involves looking at the angle between a ray and the surface's normal to calculate
 * the amount of color to render.
 */

class DiffuseRenderer extends Renderer {
  function render_ray(World $world, Encoder $img, $i, $j, Ray $ray) {
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
          $shading = max(Vector::dot($new_ray->getDirection(), $r['n']), 0);
          $c = clone $obj->getColor();
          $color = $c->K_mul($shading);
        } else {
          $color = Color::$black;
        }
      }
    }
    $img->setPixel($i, $j, $color);
  }
}
