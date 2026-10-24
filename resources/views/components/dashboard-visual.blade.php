{{-- Decorative, theme-aware illustration of an analytics workspace. Contains no real figures. --}}
<figure {{ $attributes->merge(['class' => 'relative']) }}>
    <svg viewBox="0 0 640 480" class="h-auto w-full" role="img" aria-label="Illustration of an analytics dashboard with a trend line, bar chart, scenario range and data-quality indicator">
        <defs>
            <linearGradient id="dv-area" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" style="stop-color:var(--accent);stop-opacity:.35"/>
                <stop offset="100%" style="stop-color:var(--accent);stop-opacity:0"/>
            </linearGradient>
        </defs>

        <rect x="8" y="8" width="624" height="464" rx="28" style="fill:var(--surface);stroke:var(--line)" stroke-width="1.5"/>
        <circle cx="40" cy="38" r="5" style="fill:var(--line)"/><circle cx="58" cy="38" r="5" style="fill:var(--line)"/><circle cx="76" cy="38" r="5" style="fill:var(--line)"/>
        <rect x="420" y="30" width="180" height="16" rx="8" style="fill:var(--line)"/>

        <g>
            <rect x="32" y="68" width="176" height="84" rx="16" style="fill:var(--canvas-soft);stroke:var(--line)"/>
            <rect x="48" y="86" width="64" height="8" rx="4" style="fill:var(--muted);opacity:.5"/>
            <rect x="48" y="108" width="104" height="18" rx="6" style="fill:var(--ink);opacity:.9"/>
            <rect x="48" y="134" width="48" height="6" rx="3" style="fill:var(--accent)"/>

            <rect x="232" y="68" width="176" height="84" rx="16" style="fill:var(--canvas-soft);stroke:var(--line)"/>
            <rect x="248" y="86" width="72" height="8" rx="4" style="fill:var(--muted);opacity:.5"/>
            <rect x="248" y="108" width="88" height="18" rx="6" style="fill:var(--ink);opacity:.9"/>
            <rect x="248" y="134" width="56" height="6" rx="3" style="fill:var(--accent)"/>

            <rect x="432" y="68" width="176" height="84" rx="16" style="fill:var(--canvas-soft);stroke:var(--line)"/>
            <rect x="448" y="86" width="56" height="8" rx="4" style="fill:var(--muted);opacity:.5"/>
            <rect x="448" y="108" width="112" height="18" rx="6" style="fill:var(--ink);opacity:.9"/>
            <rect x="448" y="134" width="40" height="6" rx="3" style="fill:var(--accent)"/>
        </g>

        <g>
            <rect x="32" y="172" width="376" height="268" rx="18" style="fill:var(--canvas-soft);stroke:var(--line)"/>
            <rect x="52" y="192" width="120" height="9" rx="4.5" style="fill:var(--muted);opacity:.55"/>
            <g style="stroke:var(--line)" stroke-width="1">
                <path d="M52 250H388M52 300H388M52 350H388M52 400H388"/>
            </g>
            <path d="M52 380 C100 360 120 340 164 336 S236 300 276 282 S340 240 388 214 L388 410 L52 410 Z" fill="url(#dv-area)"/>
            <path d="M52 380 C100 360 120 340 164 336 S236 300 276 282 S340 240 388 214" fill="none" style="stroke:var(--accent)" stroke-width="3.5" stroke-linecap="round"/>
            <path d="M52 392 C110 380 150 368 200 352 S300 320 388 290" fill="none" style="stroke:var(--muted)" stroke-width="2" stroke-dasharray="6 7" stroke-linecap="round"/>
            <circle cx="276" cy="282" r="6" style="fill:var(--canvas);stroke:var(--accent)" stroke-width="3"/>
        </g>

        <g>
            <rect x="432" y="172" width="176" height="128" rx="18" style="fill:var(--canvas-soft);stroke:var(--line)"/>
            <rect x="450" y="190" width="72" height="8" rx="4" style="fill:var(--muted);opacity:.55"/>
            <g style="fill:var(--accent)">
                <rect x="452" y="256" width="20" height="26" rx="4" opacity=".45"/>
                <rect x="482" y="240" width="20" height="42" rx="4" opacity=".6"/>
                <rect x="512" y="224" width="20" height="58" rx="4" opacity=".75"/>
                <rect x="542" y="208" width="20" height="74" rx="4"/>
                <rect x="572" y="232" width="20" height="50" rx="4" opacity=".7"/>
            </g>

            <rect x="432" y="316" width="176" height="124" rx="18" style="fill:var(--canvas-soft);stroke:var(--line)"/>
            <rect x="450" y="334" width="88" height="8" rx="4" style="fill:var(--muted);opacity:.55"/>
            <circle cx="500" cy="394" r="30" fill="none" style="stroke:var(--line)" stroke-width="10"/>
            <circle cx="500" cy="394" r="30" fill="none" style="stroke:var(--accent)" stroke-width="10" stroke-linecap="round" stroke-dasharray="132 188" transform="rotate(-90 500 394)"/>
            <rect x="548" y="376" width="42" height="7" rx="3.5" style="fill:var(--ink);opacity:.8"/>
            <rect x="548" y="392" width="30" height="7" rx="3.5" style="fill:var(--muted);opacity:.6"/>
            <rect x="548" y="408" width="38" height="7" rx="3.5" style="fill:var(--muted);opacity:.6"/>
        </g>
    </svg>
    <figcaption class="site-muted mt-3 text-center text-xs">Illustrative workspace — no real client data.</figcaption>
</figure>
