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

class Camera extends Object {
  protected $direction;
  protected $angle;
  protected $up;
  protected $right;

  public function __construct() {
    parent::__construct();

    // Assume that up is always somewhere in the <0, 1, 0> <direction> plane
    $this->up = new Vector(0, 1, 0);

    $this->angle = 30 / 180 * M_PI;
  }

  public function getDirection() {
    return $this->direction;
  }

  public function getAngle() {
    return $this->angle;
  }

  public function getRight() {
    return $this->right;
  }

  public function getUp() {
    return $this->up;
  }

  public function setLookAt(Vector $v) {
    // Compute direction (<look at> - <position>)
    $this->direction = clone $v;
    $this->direction->V_sub($this->position);

    my_assert(!$this->direction->isNull(), 'look at == position');

    // Normalize
    $this->direction->normalize();

    // Compute <right>
    $this->right = clone $this->up;
    $this->right->V_cross($this->direction);

    // Compute the real <up>
    $this->up = clone $this->direction;
    $this->up->V_cross($this->right);

    return $this;
  }

  public function intersect(Ray $r, World $w, $iter) {
    my_assert(false);
  }
}
