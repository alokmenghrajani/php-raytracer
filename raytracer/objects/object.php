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

abstract class Object {
  protected $name;
  protected $position;
  protected $color;

  public function __construct($name) {
    $this->name = $name;
    $this->position = new Vector(0, 0, 0);
    $this->color = Color::$white;
  }

  public function getName() {
    return $this->name;
  }

  public function setPosition(Vector $v) {
    $this->position = $v;
    return $this;
  }

  public function getPosition() {
    return $this->position;
  }

  public function setColor(Color $c) {
    $this->color = $c;
    return $this;
  }

  public function getColor() {
    return $this->color;
  }

  /**
   * Calculates intersection between the Ray $r and this object.
   *
   * Returns null if the ray does not intersect the object. If it
   * does, we return an array(
   *   'd' => distance from ray's origin,
   *   'p' => point of intersection (if $compute_point is true),
   *   'n' => normal vector (if $compute_normal is true)
   * )
   */
  abstract function intersect(Ray $r, $compute_point, $compute_normal);
}
