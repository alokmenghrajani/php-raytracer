What is php-raytracer?
======================

This is a ray tracing software written in PHP. It's pretty much pointless, I just wrote it "for the lolz".

What is a ray tracer?
=====================

A ray tracer is a piece of software which generate 3D images by simulating the physics of light. The result
is a very realistic 3D image.

[sample image](https://github.com/alokmenghrajani/php-raytracer/blob/master/images/sample_04.png).

If you want to read more about ray tracing, you should checkout www.povray.org (a real raytracer).

Why write pointless code?
=========================

I decided to share various pieces of functional code (dubbed "for the lolz"). These pieces of code are
meant to illustrate fundamental computer science concepts, so someone looking at this code might learn
something useful.

If you are teaching programming and you find a use in these projects, feel free to use my code however
you like.

Coordinate System
=================

The coordinate system is the following:
* x points right
* y points up
* z points away from you

This system is also known as the "left-hand".

Running the sample code
=======================

For Linux or Mac OS:
--------------------
git clone git://github.com/alokmenghrajani/php-raytracer.git
cd php-raytracer
php sample_01.php

and then open images/sample_01.bmp in your favourite image viewer.



