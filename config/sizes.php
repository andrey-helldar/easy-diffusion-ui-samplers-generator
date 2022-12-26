<?php

declare(strict_types=1);

return [
    /*
     * Image size to generate.
     * This option sets both the width and height of the generated image.
     * 
     * The recommended cell size is 512 pixels.
     */

    'cell' => 512,

    /*
     * This parameter sets the size of the text displayed on the samplers.
     * 
     * Recommended sizes:
     *   For cell size 512:
     *     header: 72
     *     parameters: 28
     * 
     *   For cell size 256:
     *     header: 28
     *     parameters: 12
     */

    'font' => [
        'header'     => 72,
        'parameters' => 28,
    ],
];
