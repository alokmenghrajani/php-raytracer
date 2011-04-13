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
 * A simple world, with 2 spheres and a plane.
 * Rendered using diffuse rendering.
 */

include_once('raytracer/includes.php');

$camera = id(new Camera())
  ->setPosition(new Vector(10, 30, -100))
  ->setLookAt(new Vector(0, 10, 0));

$light = id(new PointLight())
  ->setPosition(new Vector(100, 100, -100));

$sphere = id(new Sphere('red sphere'))
  ->setPosition(new Vector(0, 0, 0))
  ->setRadius(10)
  ->setColor(Color::$red);

$sphere2 = id(new Sphere('green sphere'))
  ->setPosition(new Vector(0, 18, 0))
  ->setRadius(10)
  ->setColor(Color::$green);

$plane = id(new Plane('floor'))
  ->setPosition(new Vector(0, -10, 0))
  ->setNormal(new Vector(0, 1, 0))
  ->setColor(Color::$blue);

$renderer = new DiffuseRenderer();

$world = id(new World())
  ->setCamera($camera)
  ->addObject($sphere)
  ->addObject($sphere2)
  ->addObject($plane)
  ->addLight($light)
  ->setRenderer($renderer);

$world->render('images/sample_05', 400, 225);
