//
const themeButtons = document.querySelectorAll('[data-theme-choice]');
const themeRoot = document.documentElement;
const storedTheme = window.localStorage.getItem('investai-theme');

if (storedTheme && ['midnight', 'cobalt', 'ivory'].includes(storedTheme)) {
    themeRoot.dataset.theme = storedTheme;
}

const refreshThemeButtons = () => {
    themeButtons.forEach((button) => {
        const isActive = button.dataset.themeChoice === themeRoot.dataset.theme;
        button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
    });
};

themeButtons.forEach((button) => {
    button.addEventListener('click', () => {
        themeRoot.dataset.theme = button.dataset.themeChoice;
        window.localStorage.setItem('investai-theme', button.dataset.themeChoice);
        refreshThemeButtons();
    });
});

refreshThemeButtons();

const revealTargets = document.querySelectorAll('.scroll-reveal');

if (revealTargets.length > 0) {
    if ('IntersectionObserver' in window) {
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });

        revealTargets.forEach((target) => revealObserver.observe(target));
    } else {
        revealTargets.forEach((target) => target.classList.add('is-visible'));
    }
}

const readinessRoot = document.querySelector('[data-readiness]');

if (readinessRoot) {
    const config = JSON.parse(readinessRoot.dataset.config);
    const form = readinessRoot.querySelector('[data-readiness-form]');
    const progress = readinessRoot.querySelector('[data-progress]');
    const results = readinessRoot.querySelector('[data-results]');
    const placeholder = results.querySelector('[data-results-placeholder]');
    const body = results.querySelector('[data-results-body]');
    const fieldsets = Array.from(form.querySelectorAll('fieldset'));
    const maxPerDimension = 8;

    const answeredCount = () => fieldsets.filter((fieldset) => fieldset.querySelector('input:checked')).length;

    const updateProgress = () => {
        progress.textContent = `${answeredCount()} of ${fieldsets.length} answered`;
    };

    const makeText = (tag, className, text) => {
        const element = document.createElement(tag);
        element.className = className;
        element.textContent = text;

        return element;
    };

    const scoreDimensions = () => {
        const totals = {};

        fieldsets.forEach((fieldset) => {
            const checked = fieldset.querySelector('input:checked');
            totals[fieldset.dataset.dimension] = (totals[fieldset.dataset.dimension] || 0) + Number(checked ? checked.value : 0);
        });

        return totals;
    };

    const showResults = () => {
        const totals = scoreDimensions();
        const overall = Object.values(totals).reduce((sum, value) => sum + value, 0);
        const level = config.levels.find((candidate) => overall <= candidate.max) || config.levels[config.levels.length - 1];

        body.querySelector('[data-level-name]').textContent = `${level.name} (${overall}/32)`;
        body.querySelector('[data-level-text]').textContent = level.text;

        const bars = body.querySelector('[data-dimension-bars]');
        bars.replaceChildren();

        Object.entries(config.dimensions).forEach(([key, dimension]) => {
            const score = totals[key] || 0;
            const item = document.createElement('li');
            item.className = 'grid gap-2';

            const header = document.createElement('div');
            header.className = 'flex items-center justify-between gap-4 text-sm';
            header.append(makeText('span', 'font-semibold', dimension.label), makeText('span', 'site-muted', `${score}/${maxPerDimension}`));

            const meter = document.createElement('div');
            meter.className = 'meter';
            meter.setAttribute('role', 'img');
            meter.setAttribute('aria-label', `${dimension.label}: ${score} out of ${maxPerDimension}`);
            const fill = document.createElement('span');
            fill.style.width = '0%';
            meter.append(fill);
            requestAnimationFrame(() => { fill.style.width = `${(score / maxPerDimension) * 100}%`; });

            item.append(header, meter);
            bars.append(item);
        });

        const steps = body.querySelector('[data-next-steps]');
        steps.replaceChildren();

        Object.entries(config.dimensions)
            .map(([key, dimension]) => ({ ...dimension, score: totals[key] || 0 }))
            .sort((a, b) => a.score - b.score)
            .slice(0, 2)
            .forEach((dimension) => {
                const item = document.createElement('li');
                item.className = 'grid gap-1';
                const link = makeText('a', 'site-accent font-semibold', `${dimension.label} → ${dimension.service}`);
                link.href = dimension.url;
                item.append(link, makeText('p', 'site-muted text-sm leading-6', dimension.advice));
                steps.append(item);
            });

        body.querySelector('[data-contact-link]').href = config.contactUrl;
        placeholder.classList.add('hidden');
        body.classList.remove('hidden');
        body.classList.add('grid');
        results.focus();
    };

    form.addEventListener('change', updateProgress);

    form.addEventListener('submit', (event) => {
        event.preventDefault();

        const firstUnanswered = fieldsets.find((fieldset) => ! fieldset.querySelector('input:checked'));

        if (firstUnanswered) {
            progress.textContent = `${fieldsets.length - answeredCount()} question(s) still to answer`;
            firstUnanswered.scrollIntoView({ behavior: 'smooth', block: 'center' });

            return;
        }

        showResults();
    });

    body.querySelector('[data-reset]').addEventListener('click', () => {
        form.reset();
        updateProgress();
        body.classList.add('hidden');
        body.classList.remove('grid');
        placeholder.classList.remove('hidden');
        form.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });

    updateProgress();
}

const estimatorRoot = document.querySelector('[data-estimator]');

if (estimatorRoot) {
    const input = (name) => estimatorRoot.querySelector(`[data-input="${name}"]`);
    const output = (name) => estimatorRoot.querySelector(`[data-output="${name}"]`);
    const currency = new Intl.NumberFormat('en-GB', { style: 'currency', currency: 'GBP', maximumFractionDigits: 0 });
    const number = new Intl.NumberFormat('en-GB', { maximumFractionDigits: 0 });
    const weeksPerYear = 46;
    const hoursPerFullTimeYear = weeksPerYear * 37.5;

    const readNumber = (name, minimum, maximum) => {
        const value = Number.parseFloat(input(name).value);

        return Number.isFinite(value) ? Math.min(Math.max(value, minimum), maximum) : 0;
    };

    const calculate = () => {
        const people = readNumber('people', 0, 1000);
        const hours = readNumber('hours', 0, 60);
        const rate = readNumber('rate', 0, 1000);
        const share = readNumber('share', 10, 80) / 100;

        const hoursTotal = people * hours * weeksPerYear;
        const hoursSaved = hoursTotal * share;

        output('share').textContent = `${Math.round(share * 100)}%`;
        output('hours-total').textContent = number.format(hoursTotal);
        output('cost-total').textContent = `${currency.format(hoursTotal * rate)} of staff time a year`;
        output('hours-saved').textContent = number.format(hoursSaved);
        output('cost-saved').textContent = currency.format(hoursSaved * rate);
        output('fte-saved').textContent = (hoursSaved / hoursPerFullTimeYear).toFixed(1);
    };

    estimatorRoot.addEventListener('input', calculate);
    calculate();
}

const riskRoot = document.querySelector('[data-risk]');

if (riskRoot) {
    const config = JSON.parse(riskRoot.dataset.config);
    const classes = config.assumptions.classes;
    const correlations = config.assumptions.correlations;
    const keys = Object.keys(classes);
    const form = riskRoot.querySelector('[data-risk-form]');
    const results = riskRoot.querySelector('[data-risk-results]');
    const aiPanel = riskRoot.querySelector('[data-ai-panel]');
    const find = (selector) => riskRoot.querySelector(selector);
    const output = (name) => riskRoot.querySelector(`[data-output="${name}"]`);
    const currency = new Intl.NumberFormat('en-GB', { style: 'currency', currency: 'GBP', maximumFractionDigits: 0 });
    const badYearZ = 1.645;
    const veryBadYearZ = 2.326;

    const weightInput = (key) => riskRoot.querySelector(`[data-weight="${key}"]`);
    const readWeights = () => Object.fromEntries(keys.map((key) => [key, Math.min(Math.max(Number.parseFloat(weightInput(key).value) || 0, 0), 100)]));
    const percent = (value, digits = 1) => `${value.toFixed(digits)}%`;
    const signedPercent = (value) => `${value < 0 ? '−' : ''}${Math.abs(value).toFixed(1)}%`;

    const band = (volatility) => {
        if (volatility < 4) { return 'Low'; }
        if (volatility < 7) { return 'Moderately low'; }
        if (volatility < 11) { return 'Moderate'; }
        if (volatility < 15) { return 'Moderately high'; }

        return 'High';
    };

    const estimate = (weightsPercent, years, tolerance) => {
        const weights = Object.fromEntries(keys.map((key) => [key, weightsPercent[key] / 100]));
        let mean = 0;
        let variance = 0;
        let growthShare = 0;
        let weightedVolatility = 0;

        keys.forEach((key) => {
            mean += weights[key] * classes[key].expectedReturn / 100;
            weightedVolatility += weights[key] * classes[key].volatility / 100;
            growthShare += classes[key].growth ? weights[key] : 0;

            keys.forEach((other) => {
                variance += weights[key] * weights[other] * (classes[key].volatility / 100) * (classes[other].volatility / 100) * correlations[key][other];
            });
        });

        const volatility = Math.sqrt(variance);
        const medianGrowth = mean - (volatility ** 2) / 2;
        const spread = volatility * Math.sqrt(years);
        const largestHolding = Math.max(...Object.values(weights)) * 100;
        const badYear = (mean - badYearZ * volatility) * 100;
        const loss = Math.max(0, -badYear);
        const flags = [];

        if (largestHolding >= 60) { flags.push('Concentrated: more than 60% sits in a single asset class.'); }
        if (growthShare * 100 >= 80) { flags.push('Growth-heavy: shares, property and alternatives make up 80% or more, and they tend to fall together in stress.'); }
        if (years <= 3 && growthShare * 100 >= 50) { flags.push('Short horizon: with three years or fewer, a large fall may not have time to recover.'); }

        return {
            expectedReturn: mean * 100,
            volatility: volatility * 100,
            badYear,
            veryBadYear: (mean - veryBadYearZ * volatility) * 100,
            lowMultiple: Math.exp(medianGrowth * years - badYearZ * spread),
            medianMultiple: Math.exp(medianGrowth * years),
            highMultiple: Math.exp(medianGrowth * years + badYearZ * spread),
            band: band(volatility * 100),
            toleranceStatus: loss <= tolerance ? 'within' : (loss <= tolerance * 1.25 ? 'close' : 'above'),
            loss,
            flags,
        };
    };

    const renderStack = (weights) => {
        const stack = find('[data-stack]');
        stack.replaceChildren();

        keys.forEach((key, index) => {
            const segment = document.createElement('span');
            segment.style.width = `${weights[key]}%`;
            segment.style.background = 'var(--accent)';
            segment.style.opacity = String(0.3 + index * 0.175);
            segment.title = `${classes[key].label}: ${weights[key]}%`;
            stack.append(segment);
        });
    };

    const verdictText = (status, loss, tolerance) => {
        const lossText = `about ${loss.toFixed(1)}%`;

        if (status === 'within') {
            return `In a bad (1-in-20) year this mix could fall ${lossText}, which is within the ${tolerance}% fall you said you could tolerate. A very bad year could still go beyond it.`;
        }

        if (status === 'close') {
            return `In a bad (1-in-20) year this mix could fall ${lossText}, which is close to the ${tolerance}% limit you said you could tolerate — a slightly worse year would exceed it.`;
        }

        return `In a bad (1-in-20) year this mix could fall ${lossText}, which is above the ${tolerance}% fall you said you could tolerate. It may be worth reconsidering either the mix or the tolerance.`;
    };

    const clearAiOutput = () => {
        const target = find('[data-ai-output]');

        if (target) {
            target.replaceChildren();
        }
    };

    const state = { weights: null, years: 10, tolerance: 15, valid: true };

    const calculate = () => {
        const weights = readWeights();
        const total = Object.values(weights).reduce((sum, value) => sum + value, 0);
        const years = Number.parseInt(find('[data-years]').value, 10);
        const amount = Math.max(Number.parseFloat(find('[data-amount]').value) || 0, 0);
        const tolerance = Number.parseInt(find('[data-tolerance]').value, 10);
        const totalElement = find('[data-total]');
        const valid = Math.abs(total - 100) <= 0.5;

        totalElement.textContent = valid ? `Total: ${Number(total.toFixed(1))}%` : `Total: ${Number(total.toFixed(1))}% — please make the mix add up to 100%`;
        totalElement.classList.toggle('text-rose-400', ! valid);
        output('years').textContent = `${years} ${years === 1 ? 'year' : 'years'}`;

        Object.assign(state, { weights, years, tolerance, valid });
        results.classList.toggle('opacity-50', ! valid);

        const aiButton = find('[data-ai-button]');

        if (aiButton) {
            aiButton.disabled = ! valid || ! find('[data-ai-consent]').checked;
        }

        if (! valid) {
            return;
        }

        const metrics = estimate(weights, years, tolerance);

        renderStack(weights);
        find('[data-band]').textContent = `${metrics.band} risk`;
        output('volatility').textContent = percent(metrics.volatility);
        output('expected').textContent = percent(metrics.expectedReturn);
        output('bad').textContent = signedPercent(metrics.badYear);
        output('verybad').textContent = signedPercent(metrics.veryBadYear);
        output('bad-amount').textContent = amount ? `${metrics.badYear < 0 ? '−' : ''}${currency.format(Math.abs(amount * metrics.badYear / 100))} on ${currency.format(amount)}` : '';
        output('verybad-amount').textContent = amount ? `${metrics.veryBadYear < 0 ? '−' : ''}${currency.format(Math.abs(amount * metrics.veryBadYear / 100))} on ${currency.format(amount)}` : '';
        output('range-title').textContent = `Possible range after ${years} ${years === 1 ? 'year' : 'years'}`;
        output('range').textContent = amount
            ? `${currency.format(amount * metrics.lowMultiple)} – ${currency.format(amount * metrics.highMultiple)} (middle ${currency.format(amount * metrics.medianMultiple)})`
            : `${metrics.lowMultiple.toFixed(2)}× – ${metrics.highMultiple.toFixed(2)}× the amount invested (middle ${metrics.medianMultiple.toFixed(2)}×)`;
        output('verdict').textContent = verdictText(metrics.toleranceStatus, metrics.loss, tolerance);

        const flagList = find('[data-flags]');
        flagList.replaceChildren(...metrics.flags.map((flag) => {
            const item = document.createElement('li');
            item.textContent = flag;

            return item;
        }));
    };

    const setMode = () => {
        const aiSelected = form.querySelector('input[name="mode"]:checked').value === 'ai';
        aiPanel.classList.toggle('hidden', ! aiSelected);
        aiPanel.classList.toggle('grid', aiSelected);
    };

    const appendExplanation = (text) => {
        const target = find('[data-ai-output]');
        target.replaceChildren();

        text.split(/\n+/).map((line) => line.trim()).filter(Boolean).forEach((line) => {
            const heading = line.startsWith('## ');
            const element = document.createElement(heading ? 'h4' : 'p');
            element.className = heading ? 'mt-2 font-semibold' : 'site-muted leading-7';
            element.textContent = heading ? line.slice(3) : line;
            target.append(element);
        });
    };

    const showAiMessage = (message) => {
        const target = find('[data-ai-output]');
        const element = document.createElement('p');
        element.className = 'text-sm text-rose-400';
        element.textContent = message;
        target.replaceChildren(element);
    };

    const requestExplanation = async () => {
        const button = find('[data-ai-button]');
        const target = find('[data-ai-output]');
        const label = button.textContent;

        button.disabled = true;
        button.textContent = 'Explaining…';
        target.replaceChildren(Object.assign(document.createElement('p'), { className: 'site-muted text-sm', textContent: 'Asking the AI service — this can take up to half a minute.' }));

        try {
            const response = await fetch(config.explainUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                },
                body: JSON.stringify({ allocation: state.weights, years: state.years, tolerance: state.tolerance, consent: true }),
            });
            const payload = await response.json().catch(() => ({}));

            if (! response.ok) {
                showAiMessage(response.status === 429
                    ? 'You have reached the limit for AI explanations for now. Please try again later — the calculation above still works.'
                    : (payload.message || 'The AI explanation is unavailable right now.'));

                return;
            }

            appendExplanation(payload.explanation);
        } catch {
            showAiMessage('The AI explanation could not be reached. The calculation above still stands.');
        } finally {
            button.textContent = label;
            button.disabled = ! state.valid || ! find('[data-ai-consent]').checked;
        }
    };

    form.addEventListener('input', () => { clearAiOutput(); calculate(); });
    form.addEventListener('change', (event) => {
        if (event.target.name === 'mode') {
            setMode();
        }

        calculate();
    });

    form.querySelectorAll('[data-preset]').forEach((button) => {
        button.addEventListener('click', () => {
            const preset = config.presets[button.dataset.preset];
            keys.forEach((key) => { weightInput(key).value = preset[key]; });
            clearAiOutput();
            calculate();
        });
    });

    const aiButton = find('[data-ai-button]');

    if (aiButton) {
        aiButton.addEventListener('click', requestExplanation);
    }

    form.addEventListener('submit', (event) => event.preventDefault());
    setMode();
    calculate();
}
