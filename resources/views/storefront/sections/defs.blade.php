<!-- Shared pattern library (used by every section) -->
<svg aria-hidden="true" width="0" height="0" style="position:absolute; width:0; height:0; overflow:hidden">
  <defs>
    <!-- 8-petal rosette, 48 x 48 -->
    <g id="rosetteArt" fill="none" stroke="currentColor" stroke-width="1.2">
      <ellipse cx="24" cy="24" rx="3.4" ry="17"></ellipse>
      <ellipse cx="24" cy="24" rx="3.4" ry="17" transform="rotate(45 24 24)"></ellipse>
      <ellipse cx="24" cy="24" rx="3.4" ry="17" transform="rotate(90 24 24)"></ellipse>
      <ellipse cx="24" cy="24" rx="3.4" ry="17" transform="rotate(135 24 24)"></ellipse>
      <circle cx="24" cy="24" r="20" stroke-dasharray="1.4 2.6"></circle>
      <circle cx="24" cy="24" r="23"></circle>
      <circle cx="24" cy="24" r="8"></circle>
      <circle cx="24" cy="24" r="3" fill="currentColor"></circle>
      <path d="M24 12L28 24L24 36L20 24Z" stroke-width="0.9"></path>
      <path d="M12 24L24 20L36 24L24 28Z" stroke-width="0.9"></path>
    </g>

    <!-- Ornament line with rosette, 180 x 24 -->
    <g id="ornArt" fill="none" stroke="currentColor" stroke-width="1.2">
      <path d="M2 12H64M116 12H178"></path>
      <path d="M10 8H58M122 8H170" stroke-dasharray="1.4 3"></path>
      <path d="M10 16H58M122 16H170" stroke-dasharray="1.4 3"></path>
      <path d="M70 12L74 8L78 12L74 16ZM102 12L106 8L110 12L106 16Z"></path>
      <g transform="translate(78 0) scale(0.5)"><use href="#rosetteArt"></use></g>
    </g>

    <!-- Jamdani band, 120 x 48 -->
    <g id="bandArt" fill="none" stroke="currentColor" stroke-width="1.2">
      <path d="M0 2.5H120M0 5.5H120M0 42.5H120M0 45.5H120"></path>
      <polyline points="0,10 5,14 10,10 15,14 20,10 25,14 30,10 35,14 40,10 45,14 50,10 55,14 60,10 65,14 70,10 75,14 80,10 85,14 90,10 95,14 100,10 105,14 110,10 115,14 120,10"></polyline>
      <polyline points="0,38 5,34 10,38 15,34 20,38 25,34 30,38 35,34 40,38 45,34 50,38 55,34 60,38 65,34 70,38 75,34 80,38 85,34 90,38 95,34 100,38 105,34 110,38 115,34 120,38"></polyline>
      <ellipse cx="60" cy="24" rx="3" ry="10"></ellipse>
      <ellipse cx="60" cy="24" rx="3" ry="10" transform="rotate(45 60 24)"></ellipse>
      <ellipse cx="60" cy="24" rx="3" ry="10" transform="rotate(90 60 24)"></ellipse>
      <ellipse cx="60" cy="24" rx="3" ry="10" transform="rotate(135 60 24)"></ellipse>
      <circle cx="60" cy="24" r="12.5" stroke-dasharray="1.4 2.6"></circle>
      <circle cx="60" cy="24" r="2.6" fill="currentColor"></circle>
      <path d="M24 14L34 24L24 34L14 24Z"></path>
      <path d="M24 18.5L29.5 24L24 29.5L18.5 24Z"></path>
      <circle cx="24" cy="24" r="1.4" fill="currentColor"></circle>
      <path d="M96 14L106 24L96 34L86 24Z"></path>
      <path d="M96 18.5L101.5 24L96 29.5L90.5 24Z"></path>
      <circle cx="96" cy="24" r="1.4" fill="currentColor"></circle>
      <path d="M36 24H46M74 24H84M108 24H120M0 24H12" stroke-dasharray="1.4 2.6"></path>
      <path d="M40 18L44 24L40 30M80 18L76 24L80 30"></path>
      <circle cx="60" cy="9" r="1.2" fill="currentColor"></circle>
      <circle cx="60" cy="39" r="1.2" fill="currentColor"></circle>
    </g>

    <!-- Jali lattice with 8-point star, 64 x 64 -->
    <g id="jaliArt" fill="none" stroke="currentColor" stroke-width="1">
      <path d="M14 14H50V50H14Z"></path>
      <path d="M32 6L58 32L32 58L6 32Z"></path>
      <circle cx="32" cy="32" r="11"></circle>
      <circle cx="32" cy="32" r="5"></circle>
      <circle cx="32" cy="32" r="1.6" fill="currentColor"></circle>
      <path d="M14 14L0 0M50 14L64 0M50 50L64 64M14 50L0 64"></path>
      <circle cx="0" cy="0" r="3"></circle><circle cx="64" cy="0" r="3"></circle><circle cx="0" cy="64" r="3"></circle><circle cx="64" cy="64" r="3"></circle>
      <path d="M32 0V6M32 58V64M0 32H6M58 32H64"></path>
    </g>

    <!-- Kantha running stitch, 60 x 10 -->
    <g id="stitchArt" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round">
      <path d="M0 5q7.5-4 15 0t15 0t15 0t15 0" stroke-dasharray="5 4"></path>
    </g>

    <!-- Corner fan, 56 x 56 -->
    <g id="cornerArt" fill="none" stroke="currentColor" stroke-width="1.2">
      <path d="M44 0A12 12 0 0 0 56 12"></path>
      <path d="M34 0A22 22 0 0 0 56 22"></path>
      <path d="M24 0A32 32 0 0 0 56 32" stroke-dasharray="1.4 2.6"></path>
      <path d="M14 0A42 42 0 0 0 56 42"></path>
      <path d="M56 0L30 26" stroke-dasharray="1.4 2.6"></path>
      <circle cx="56" cy="0" r="3" fill="currentColor"></circle>
    </g>

    <pattern id="patBandGold" width="120" height="48" patternUnits="userSpaceOnUse"><use href="#bandArt" style="color:#B8892F"></use></pattern>
    <pattern id="patBandLight" width="120" height="48" patternUnits="userSpaceOnUse"><use href="#bandArt" style="color:#E3C26F"></use></pattern>
    <pattern id="patJaliLight" width="64" height="64" patternUnits="userSpaceOnUse"><use href="#jaliArt" style="color:#E3C26F; opacity:0.3"></use></pattern>
    <pattern id="patJaliDark" width="64" height="64" patternUnits="userSpaceOnUse"><use href="#jaliArt" style="color:#1F4D3A; opacity:0.13"></use></pattern>
    <pattern id="patJaliGold" width="64" height="64" patternUnits="userSpaceOnUse"><use href="#jaliArt" style="color:#B8892F; opacity:0.35"></use></pattern>
    <pattern id="patStitchGold" width="60" height="10" patternUnits="userSpaceOnUse"><use href="#stitchArt" style="color:#B8892F"></use></pattern>
    <pattern id="patStitchLight" width="60" height="10" patternUnits="userSpaceOnUse"><use href="#stitchArt" style="color:#E3C26F"></use></pattern>
    <pattern id="patDots" width="14" height="14" patternUnits="userSpaceOnUse"><circle cx="7" cy="7" r="1.1" fill="#1F4D3A" fill-opacity="0.2"></circle></pattern>

    <!-- Craft patterns that sit on top of coloured photo boxes -->
    <pattern id="patCloth" width="44" height="44" patternUnits="userSpaceOnUse">
      <path d="M22 2L42 22L22 42L2 22Z" fill="none" stroke="#FFFFFF" stroke-opacity="0.5" stroke-width="1.2"></path>
      <path d="M22 10L34 22L22 34L10 22Z" fill="none" stroke="#FFFFFF" stroke-opacity="0.5" stroke-width="1.2"></path>
      <path d="M22 16L28 22L22 28L16 22Z" fill="#FFFFFF" fill-opacity="0.22"></path>
      <circle cx="22" cy="22" r="1.6" fill="#FFFFFF" fill-opacity="0.7"></circle>
      <circle cx="0" cy="0" r="2" fill="#FFFFFF" fill-opacity="0.5"></circle><circle cx="44" cy="0" r="2" fill="#FFFFFF" fill-opacity="0.5"></circle>
      <circle cx="0" cy="44" r="2" fill="#FFFFFF" fill-opacity="0.5"></circle><circle cx="44" cy="44" r="2" fill="#FFFFFF" fill-opacity="0.5"></circle>
      <path d="M22 2V0M22 42V44M2 22H0M42 22H44" stroke="#FFFFFF" stroke-opacity="0.5" stroke-width="1.2"></path>
    </pattern>
    <pattern id="patBrass" width="40" height="40" patternUnits="userSpaceOnUse">
      <circle cx="20" cy="20" r="17" fill="none" stroke="#FFFFFF" stroke-opacity="0.45" stroke-width="1.2"></circle>
      <circle cx="20" cy="20" r="12" fill="none" stroke="#FFFFFF" stroke-opacity="0.45" stroke-width="1.2" stroke-dasharray="1.6 2.4"></circle>
      <circle cx="20" cy="20" r="7" fill="none" stroke="#FFFFFF" stroke-opacity="0.5" stroke-width="1.2"></circle>
      <circle cx="20" cy="20" r="2.2" fill="#FFFFFF" fill-opacity="0.7"></circle>
      <circle cx="0" cy="0" r="1.6" fill="#FFFFFF" fill-opacity="0.5"></circle><circle cx="40" cy="0" r="1.6" fill="#FFFFFF" fill-opacity="0.5"></circle>
      <circle cx="0" cy="40" r="1.6" fill="#FFFFFF" fill-opacity="0.5"></circle><circle cx="40" cy="40" r="1.6" fill="#FFFFFF" fill-opacity="0.5"></circle>
    </pattern>
    <pattern id="patCarpet" width="48" height="48" patternUnits="userSpaceOnUse">
      <path d="M24 0L48 24L24 48L0 24Z" fill="#FFFFFF" fill-opacity="0.09" stroke="#FFFFFF" stroke-opacity="0.5" stroke-width="1.2"></path>
      <path d="M24 9L39 24L24 39L9 24Z" fill="none" stroke="#FFFFFF" stroke-opacity="0.5" stroke-width="1.2"></path>
      <path d="M24 17L31 24L24 31L17 24Z" fill="#FFFFFF" fill-opacity="0.3"></path>
      <path d="M24 0V9M24 39V48M0 24H9M39 24H48" stroke="#FFFFFF" stroke-opacity="0.5" stroke-width="1.2"></path>
    </pattern>
    <pattern id="patClay" width="48" height="40" patternUnits="userSpaceOnUse">
      <path d="M0 8q6-6 12 0t12 0t12 0t12 0" fill="none" stroke="#FFFFFF" stroke-opacity="0.5" stroke-width="1.2"></path>
      <path d="M0 20q6-6 12 0t12 0t12 0t12 0" fill="none" stroke="#FFFFFF" stroke-opacity="0.5" stroke-width="1.2"></path>
      <path d="M0 32q6-6 12 0t12 0t12 0t12 0" fill="none" stroke="#FFFFFF" stroke-opacity="0.5" stroke-width="1.2"></path>
      <circle cx="6" cy="14" r="1.3" fill="#FFFFFF" fill-opacity="0.6"></circle><circle cx="18" cy="26" r="1.3" fill="#FFFFFF" fill-opacity="0.6"></circle>
      <circle cx="30" cy="14" r="1.3" fill="#FFFFFF" fill-opacity="0.6"></circle><circle cx="42" cy="26" r="1.3" fill="#FFFFFF" fill-opacity="0.6"></circle>
      <circle cx="6" cy="38" r="1.3" fill="#FFFFFF" fill-opacity="0.6"></circle><circle cx="30" cy="38" r="1.3" fill="#FFFFFF" fill-opacity="0.6"></circle>
    </pattern>
  </defs>
</svg>
