@props([
    'min' => 0,
    'max' => 100,
    'step' => 1,
    'value' => 0,
    'width' => '320px',
    'sliderId' => 'glass-slider-' . uniqid(),
    'onChange' => ''
])

<div class="glass-slider-wrapper" style="width: {{ $width }};">
    <!-- Slider HTML -->
    <div class="slider-container" id="{{ $sliderId }}">
        <div class="slider-progress" id="{{ $sliderId }}-progress"></div>
        <div class="slider-thumb-glass" id="{{ $sliderId }}-thumb">
            <div class="slider-thumb-glass-filter"></div>
            <div class="slider-thumb-glass-overlay"></div>
            <div class="slider-thumb-glass-specular"></div>
        </div>
    </div>

    <!-- SVG Filter -->
    <svg width="0" height="0">
        <filter id="mini-liquid-lens-{{ $sliderId }}" x="-50%" y="-50%" width="200%" height="200%">
            <feImage x="0" y="0" result="normalMap" xlink:href="data:image/svg+xml;utf8,
            <svg xmlns='http://www.w3.org/2000/svg' width='300' height='300'>
                <radialGradient id='invmap-{{ $sliderId }}' cx='50%' cy='50%' r='75%'>
                    <stop offset='0%' stop-color='rgb(128,128,255)'/>
                    <stop offset='90%' stop-color='rgb(255,255,255)'/>
                </radialGradient>
                <rect width='100%' height='100%' fill='url(#invmap-{{ $sliderId }})'/>
            </svg>" />
            <feDisplacementMap in="SourceGraphic" in2="normalMap" scale="-252" xChannelSelector="R" yChannelSelector="G"
                result="displaced" />
            <feMerge>
                <feMergeNode in="displaced" />
            </feMerge>
        </filter>
    </svg>

    <!-- CSS Styles -->
    <style>
        .glass-slider-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #{{ $sliderId }} {
            position: relative;
            width: 100%;
            height: 10px;
            background: #D6D6DA;
            border-radius: 999px;
        }

        #{{ $sliderId }}-progress {
            position: absolute;
            height: 100%;
            background: linear-gradient(117deg, #49a3fc 0%, #3681ee 100%);
            border-radius: 999px;
            width: 0%;
            z-index: 1;
        }

        #{{ $sliderId }}-thumb {
            position: absolute;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 65px;
            height: 42px;
            border-radius: 999px;
            cursor: pointer;
            z-index: 2;
            background-color: #fff;
            box-shadow: 0 1px 8px 0 rgba(0, 30, 63, 0.1), 0 0 2px 0 rgba(0, 9, 20, 0.1);
            overflow: hidden;
            transition: transform 0.15s ease, height 0.15s ease;
        }

        #{{ $sliderId }}-thumb .slider-thumb-glass-filter {
            position: absolute;
            inset: 0;
            z-index: 0;
            backdrop-filter: blur(0.6px);
            -webkit-backdrop-filter: blur(0.6px);
            filter: url(#mini-liquid-lens-{{ $sliderId }});
        }

        #{{ $sliderId }}-thumb .slider-thumb-glass-overlay {
            position: absolute;
            inset: 0;
            z-index: 1;
            background-color: rgba(255, 255, 255, 0.1);
        }

        #{{ $sliderId }}-thumb .slider-thumb-glass-specular {
            position: absolute;
            inset: 0;
            z-index: 2;
            border-radius: inherit;
            box-shadow:
                inset 1px 1px 0 rgba(69, 168, 243, 0.2),
                inset 1px 3px 0 rgba(28, 63, 90, 0.05),
                inset 0 0 22px rgb(255 255 255 / 60%),
                inset -1px -1px 0 rgba(69, 168, 243, 0.12);
        }

        #{{ $sliderId }}-thumb .slider-thumb-glass-filter,
        #{{ $sliderId }}-thumb .slider-thumb-glass-overlay,
        #{{ $sliderId }}-thumb .slider-thumb-glass-specular {
            opacity: 0;
        }

        #{{ $sliderId }}-thumb.active {
            background-color: transparent;
            box-shadow: none;
        }

        #{{ $sliderId }}-thumb.active .slider-thumb-glass-filter,
        #{{ $sliderId }}-thumb.active .slider-thumb-glass-overlay,
        #{{ $sliderId }}-thumb.active .slider-thumb-glass-specular {
            opacity: 1;
        }

        #{{ $sliderId }}-thumb:active {
            transform: translate(-50%, -50%) scaleY(0.98) scaleX(1.1);
        }
    </style>

    <!-- JavaScript -->
    <script>
        (() => {
            const slider = document.getElementById('{{ $sliderId }}');
            const progress = document.getElementById('{{ $sliderId }}-progress');
            const thumb = document.getElementById('{{ $sliderId }}-thumb');

            let isDragging = false;
            let sliderRect = slider.getBoundingClientRect();

            const min = {{ $min }};
            const max = {{ $max }};
            const step = {{ $step }};
            const initialValue = {{ $value }};

            const updateThumbAndProgress = (percent) => {
                percent = Math.max(0, Math.min(100, percent));
                const px = (percent / 100) * sliderRect.width;
                progress.style.width = `${percent}%`;
                thumb.style.left = `${px}px`;

                // Calculer la valeur basée sur min/max/step
                const value = Math.round((percent / 100) * (max - min) + min);
                const steppedValue = Math.round(value / step) * step;

                // Créer un événement personnalisé pour notifier les changements
                const event = new CustomEvent('slider-change', {
                    detail: { value: steppedValue, sliderId: '{{ $sliderId }}' }
                });
                document.dispatchEvent(event);

                // Déclencher l'événement onChange si fourni
                @if($onChange)
                    if (window.{{ $onChange }}) {
                        window.{{ $onChange }}(steppedValue);
                    }
                @endif
            }

            const getPercentFromClientX = (clientX) => {
                const offsetX = clientX - sliderRect.left;
                return (offsetX / sliderRect.width) * 100;
            }

            const onMove = (clientX) => {
                const percent = getPercentFromClientX(clientX);
                updateThumbAndProgress(percent);
            }

            const onMouseDown = (e) => {
                isDragging = true;
                sliderRect = slider.getBoundingClientRect();
                onMove(e.clientX);
                thumb.classList.add('active');
            }

            const onTouchStart = (e) => {
                isDragging = true;
                sliderRect = slider.getBoundingClientRect();
                onMove(e.touches[0].clientX);
                thumb.classList.add('active');
            }

            const onMouseMove = (e) => {
                if (isDragging) onMove(e.clientX);
            }

            const onTouchMove = (e) => {
                if (isDragging) onMove(e.touches[0].clientX);
            }

            const stopDrag = () => {
                isDragging = false;
                thumb.classList.remove('active');
            }

            const init = () => {
                sliderRect = slider.getBoundingClientRect();
                const initialPercent = ((initialValue - min) / (max - min)) * 100;
                updateThumbAndProgress(initialPercent);
            }

            // Events
            thumb.addEventListener('mousedown', onMouseDown);
            thumb.addEventListener('touchstart', onTouchStart, { passive: true });

            document.addEventListener('mousemove', onMouseMove);
            document.addEventListener('mouseup', stopDrag);
            document.addEventListener('touchmove', onTouchMove, { passive: false });
            document.addEventListener('touchend', stopDrag);

            slider.addEventListener('mousedown', (e) => {
                sliderRect = slider.getBoundingClientRect();
                onMove(e.clientX);
            });

            slider.addEventListener(
                'touchstart',
                (e) => {
                    sliderRect = slider.getBoundingClientRect();
                    onMove(e.touches[0].clientX);
                },
                { passive: true }
            );

            // Initialisation
            init();
        })();
    </script>
</div>
