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

class DiffuseRenderer extends Renderer {
  function render(World $world, Image $img, $width, $height) {
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

        // Calculate color of way
        foreach ($world->getObjects() as $obj) {
          $obj->intersect($ray, $world, 1);
        }

        $img->setPixel($i, $j, $ray->getColor());
      }
    }
  }
}
