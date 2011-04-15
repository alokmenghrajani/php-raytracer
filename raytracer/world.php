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
 * The World object. This object is a basically a container. It doesn't
 * do much, beside tie everything together.
 *
 * A World is composed of:
 * - exactly one camera
 * - one or more lights
 * - zero or more objects
 * - exactly one rendering engine
 */
class World {
  protected $camera = null;
  protected $lights = array();
  protected $objects = array();
  protected $renderer = null;

  public function __construct() {
    ini_set('memory_limit', '2000M');
    // So that each run returns the exact same image
    mt_srand(0);
  }

  // Sets the camera for the world. Each world should
  // have exactly one camera before rendering can happen
  public function setCamera(Camera $camera) {
    if ($this->camera) {
      throw new Exception('Camera already set');
    }
    $this->camera = $camera;
    return $this;
  }

  public function getCamera() {
    return $this->camera;
  }

  public function setRenderer(Renderer $renderer) {
    if ($this->renderer) {
      throw new Exception('Renderer already set');
    }
    $this->renderer = $renderer;
    return $this;
  }

  // Adds a light source to the world. A world should have
  // one or more lights.
  public function addLight(Light $light) {
    $this->lights[] = $light;
    return $this;
  }

  public function getLights() {
    return $this->lights;
  }

  public function addObject(Object $obj) {
    $this->objects[] = $obj;
    return $this;
  }

  public function getObjects() {
    return $this->objects;
  }

  public function render($file, $width=400, $height=225) {
    if (!$this->camera) {
      throw new Exception('You need to set a Camera');
    }
    if (!$this->lights) {
      throw new Exception('You need one or more Lights');
    }
    if (!$this->renderer) {
      throw new Exception('You need to set a Renderer');
    }

    if (function_exists('gd_info')) {
      $img = new GDEncoder($width, $height);
    } else {
      $img = new BMPEncoder($width, $height);
    }
    $this->renderer->render($this, $img, $width, $height);
    $img->writeFile($file);
  }
}
