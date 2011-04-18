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

abstract class Renderer {
  protected $anti_alias = false;
  protected $reflections = false;

  public function setAntiAlias($bool) {
    $this->anti_alias = $bool;
    return $this;
  }

  public function setReflection($bool) {
    $this->reflections = $bool;
    return $this;
  }

  /**
   *  Find the closest object in $world that $ray intersects.
   *
   *  Returns an array(
   *    'o' => object which intersected
   *    'd' => distance
   *    'p' => point of intersection
   *    'n' => normal vector
   *  );
   *
   *  or null if the ray does not intersect anything.
   */
  protected function rayIntersection(World $world, Ray $ray, $ignore, $compute_point, $compute_normal) {
    $result = null;
    foreach ($world->getObjects() as $obj) {
      if ($obj === $ignore) {
        continue;
      }
      $t = $obj->intersect($ray, $compute_point, $compute_normal);
      if ($t === null) {
        continue;
      }
      if (!$result || ($t['d'] < $result['d'])) {
        $result = $t;
        $result['o'] = $obj;
      }
    }
    return $result;
  }

  protected function pointLight(World $world, Vector $point, Object $ignore) {
    $ray = new Ray();
    $ray->setOrigin($point);
    foreach ($world->getLights() as $light) {
      $ray->setDirection(Vector::fromAtoB(
        $point,
        $light->getPosition()));
      $hits_light = false;
      foreach ($world->getObjects() as $obj) {
        if ($obj === $ignore) {
          continue;
        }
        if ($obj->intersect($ray, false, false) !== null) {
          $hits_light = true;
          break;
        }
      }
      if (!$hits_light) {
        return $ray;
      }
    }
    return null;
  }

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

        $new_i = $i;
        $new_j = $j;
        if ($this->anti_alias) {
          $new_i += mt_rand() / mt_getrandmax();
          $new_j += mt_rand() / mt_getrandmax();
        }

        $t = clone $camera->getRight();
        $t->K_mul($new_i - $width / 2);
        $r->V_add($t);

        $t = clone $camera->getUp();
        $t->K_mul($height / 2 - $new_j);
        $r->V_add($t);

        $ray = new Ray();
        $ray->setOrigin($camera->getPosition());
        $ray->setDirection($r);

        $c = $this->render_ray($world, $ray, null, 1);
        if ($c) {
          $img->setPixel($i, $j, $c);
        }
      }
    }
  }

  /**
   * Abstract ray rendering function will be implemented in the various renderers.
   *
   * Returns a color for the ray or null if the ray doesn't intersect any object.
   */
  abstract protected function render_ray(World $world, Ray $ray, $ignore, $recursion);
}
