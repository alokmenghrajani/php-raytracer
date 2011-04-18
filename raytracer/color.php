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

class Color extends Vector {
  static $white;
  static $black;
  static $red;
  static $green;
  static $blue;

  public function __construct($x, $y, $z) {
    $x = min($x, 1);
    $y = min($y, 1);
    $z = min($z, 1);

    parent::__construct($x, $y, $z);
  }

  public function toInt() {
    return (($this->x * 255 & 0xff) << 16) |
           (($this->y * 255 & 0xff) << 8) |
           ($this->z * 255 & 0xff);
  }

  public function __toString() {
    return sprintf('%02x%02x%02x', $this->x * 255 & 0xff, $this->y * 255 & 0xff, $this->z * 255 & 0xff);
  }
}

Color::$white = new Color(1, 1, 1);
Color::$black = new Color(0, 0, 0);
Color::$red   = new Color(1, 0, 0);
Color::$green = new Color(0, 1, 0);
Color::$blue  = new Color(0, 0, 1);
