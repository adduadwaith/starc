document.addEventListener("DOMContentLoaded", () => {
    const infoElement = document.querySelector(".rect2 .info");

    function getScrollY() {
        return window.scrollY || window.pageYOffset;
    }

    function isElementInViewport(el) {
        const rect = el.getBoundingClientRect();
        const scrollThreshold = rect.top + rect.height / 2;

        const isInView = scrollThreshold <= (window.innerHeight || document.documentElement.clientHeight) * 0.90;
        return isInView;
    }

    function handleScroll() {
        if (isElementInViewport(infoElement) && !infoElement.classList.contains('show-down')) {
            infoElement.classList.add('show-down');

            const textElement = document.getElementById('typing-text');
            textElement.textContent = "";

            const fullText = "The Smart Helmet provides real-time speed monitoring and alerts the rider when they exceed safe limits. It includes GPS tracking to ensure emergency responders can quickly locate the rider in case of an accident. Integrated IoT technology connects with emergency services for faster response times and improved rider safety.";
            let charIndex = 0;

            function type() {
                if (charIndex < fullText.length) {
                    textElement.textContent += fullText.charAt(charIndex);
                    charIndex++;
                    setTimeout(type, 30);
                }
            }

            type();
        }
    }

    window.addEventListener("scroll", handleScroll);
    handleScroll();

    const featuresSection = document.querySelector('.features');
    const featureBoxes = document.querySelectorAll('.feature');

    const featuresObserver = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                featureBoxes.forEach((feature, index) => {
                    setTimeout(() => {
                        feature.style.transition = 'transform 1s ease, opacity 1s ease';
                        feature.style.transform = 'translateY(0)';
                        feature.style.opacity = '1';

                        let colorInterval;

                        feature.addEventListener('mouseenter', () => {
                            feature.style.borderWidth = '4px';
                            feature.style.borderColor = 'black'; // Set border color to black
                            
                            clearInterval(colorInterval); // Stop any previous interval to prevent color change
                            
                            colorInterval = setInterval(() => {
                                feature.style.borderColor = 'black'; // Keep the border color black
                            }, 1000);
                            

                            const paragraphs = document.querySelectorAll('.feature p');
                            setInterval(() => {
                                const randomOpacity = Math.random() < 0.5 ? 0.6 : 1;
                                paragraphs.forEach(p => {
                                    p.style.opacity = randomOpacity;
                                });
                            }, 2000);
                        });

                        feature.addEventListener('mouseleave', () => {
                            clearInterval(colorInterval);
                            feature.style.borderColor = '#ccc';
                            feature.style.borderWidth = '2px';
                            const paragraphs = document.querySelectorAll('.feature p');
                            paragraphs.forEach(p => {
                                p.style.opacity = 1;
                            });
                        });

                    }, index * 400);
                });
                featuresObserver.disconnect();
            }
        });
    }, { threshold: 0.1 });

    featuresObserver.observe(featuresSection);

    const titleElement = document.querySelector(".autoShowTitle");
    if (titleElement) {
        titleElement.querySelectorAll('div').forEach((line, index) => {
            line.style.animationDelay = `${index * 0.5}s`;
        });
    }

    const featuresTitle = document.querySelector('.key-features-title');
    const featuresTitleSpans = featuresTitle?.querySelectorAll('span');

    const featuresTitleObserver = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting && featuresTitleSpans) {
                featuresTitle.classList.add('show-letters');
                featuresTitle.querySelectorAll('span').forEach((span, index) => {
                    span.style.transition = 'transform 1s ease, opacity 1s ease';
                });
                featuresTitleObserver.disconnect();
            }
        });
    }, { threshold: 0.5 });

    if (featuresTitle) {
        featuresTitleObserver.observe(featuresSection);
    }
});