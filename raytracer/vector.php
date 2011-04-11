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

class Vector {
  public $x;
  public $y;
  public $z;

  public function __construct($x, $y, $z) { // php bug ?
    $this->x = (float)$x;
    $this->y = (float)$y;
    $this->z = (float)$z;
  }

  public function equals(Vector $v2) {
    return (($this->x == $v2->x) &&
            ($this->y == $v2->y) &&
            ($this->z == $v2->z));
  }

  public function length() {
    return sqrt($this->x * $this->x +
                $this->y * $this->y +
                $this->z * $this->z);
  }

  public function normalize() {
    $this->K_div($this->length());
  }

  public function assertNormalized() {
    $l = $this->length();
    assert(($l >= 0.99) && ($l <= 1.01));
  }

  public function neg() {
    $this->x = -$this->x;
    $this->y = -$this->y;
    $this->z = -$this->z;
  }

  public function V_add(Vector $v2) {
    $this->x = $this->x + $v2->x;
    $this->y = $this->y + $v2->y;
    $this->z = $this->z + $v2->z;

    return $this;
  }

  public function V_sub(Vector $v2) {
    $this->x = $this->x - $v2->x;
    $this->y = $this->y - $v2->y;
    $this->z = $this->z - $v2->z;

    return $this;
  }

  public function V_dot(Vector $v2) {
    return ($this->x * $v2->x + $this->y * $v2->y + $this->z * $v2->z);
  }

  public function V_cross(Vector $v2) {
    $i = $this->y * $v2->z - $this->z * $v2->y;
    $j = $this->z * $v2->x - $this->x * $v2->z;
    $z = $this->x * $v2->y - $this->y * $v2->x;

    $this->x = $i;
    $this->y = $j;
    $this->z = $z;

    return $this;
  }

  public function K_mul($k) {
    $this->x = $this->x * $k;
    $this->y = $this->y * $k;
    $this->z = $this->z * $k;
    return $this;
  }

  public function K_div($k) {
    $this->x = $this->x / $k;
    $this->y = $this->y / $k;
    $this->z = $this->z / $k;
    return $this;
  }

  /**
   * @return bool
   */
  public function isNull() {
    return (($this->x == 0) &&
            ($this->y == 0) &&
            ($this->z == 0));
  }
}
