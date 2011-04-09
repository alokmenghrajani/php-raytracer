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
 * Poor man's BMP library.
 *
 * This code creates uncompressed BMP files. I'm not sure it's 100% compliant to the spec,
 * but it seems to render fine.
 */

class BMPEncoder extends Encoder {
  protected $width;
  protected $height;

  protected $pixels = array();

  public function __construct($width, $height) {
    $this->width = $width;
    $this->height = $height;

    for ($j = 0; $j < $height; $j++) {
      for ($i = 0; $i < $width; $i++) {
        $this->setPixel($i, $j, Color::$black);
      }
    }
  }

  public function setPixel($x, $y, Color $color) {
    $this->pixels[$x + $y * $this->width] = $color;
  }

  public function writeFile($file) {
    $file = $file . '.bmp';
    $fh = fopen($file, 'w');
    if (!$fh) {
      echo 'Failed to write to ', $this->file, "\n";
      return;
    }

    fwrite($fh, pack('c*',
                     0x42, 0x4d)                            // "BM"
                    );
    fwrite($fh, pack('V*',
                     $this->width * $this->height * 3 + 0x36)  // total file size
                    );
    fwrite($fh, pack('c*',
                     0x00, 0x00, 0x00, 0x00,                // reserved
                     0x36, 0x00, 0x00, 0x00,                // BOF
                     40,   0x00, 0x00, 0x00)                // no BitMapInfoHeader
                    );
    fwrite($fh, pack('V*',
                     $this->width,
                     $this->height)
                    );
    fwrite($fh, pack('c*',
                     0x01, 0x00,                            // number of planes
                     24,   0x00,                            // number of bits per pixel (24)
                     0x00, 0x00, 0x00, 0x00)                // compression type
                    );
    fwrite($fh, pack('V*',
                     $this->width * $this->height * 3)      // size of bitmap
                    );
    fwrite($fh, pack('c*',
                     0x00, 0x00, 0x00, 0x00,                // horizontal resolution
                     0x00, 0x00, 0x00, 0x00,                // vertical resolution
                     0x00, 0x00, 0x00, 0x00,                // number of used colors
                     0x00, 0x00, 0x00, 0x00)                // number of important colors
                    );

    // Dump every pixel
    for ($j = $this->height-1; $j >= 0; $j--) {
      for ($i = 0; $i < $this->width; $i++) {
        $c = $this->pixels[$i + $j * $this->width];
        fwrite($fh, pack('c*', $c->z * 255, $c->y * 255, $c->x * 255));
      }
    }

    fclose($fh);
    echo 'wrote ', $file, "\n";
  }
}
