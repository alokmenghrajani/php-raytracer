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

class Sphere extends Object {
  protected $radius = 1;

  public function intersect(Ray $ray, $compute_point, $compute_normal) {
    // A sphere-ray intersection can happen in 0, 1 or 2 points
    $dst = clone $ray->getOrigin();
    $dst->V_sub($this->position);

    $b = Vector::dot($dst, $ray->getDirection());
    $c = Vector::dot($dst, $dst) - $this->radius * $this->radius;
    $d = $b * $b - $c;
    if ($d < 0) {
      return null;
    }

    $t = -$b - sqrt($d);
    if ($t < 0) {
      return null;
    }

    $r = array('d' => $t);

    if ($compute_point || $compute_normal) {
      $t2 = clone $ray->getDirection();
      $t2->K_mul($t);
      $t2->V_add($ray->getOrigin());

      $r['p'] = $t2;
    }

    if ($compute_normal) {
      $n = clone $this->position;
      $n->neg();
      $n->V_add($t2);
      $n->normalize();

      $r['n'] = $n;
    }
    return $r;
  }

  public function setRadius($r) {
    $this->radius = (float)$r;
    return $this;
  }
}

