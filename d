document.addEventListener('DOMContentLoaded', (event) => {
    function calculatePercentage(avant, apres) {
        if (avant == 0) {
            return 0;
        }
        return ((avant - apres) / avant * 100).toFixed(2) + '%';
    }

    function calculatePercentage1(avant, apres) {
        if (avant == 0) {
            return 0;
        }
        return ((avant - apres) / avant * 100).toFixed(8) + '%';
    }

    const avantScolarite = document.getElementById('avantScolaritemodif');
    const apresScolarite = document.getElementById('apresScolaritemodif');
    const reductionScolarite = document.getElementById('modalReductionScolarite');

    avantScolarite.addEventListener('input', () => {
        reductionScolarite.value = calculatePercentage1(parseFloat(avantScolarite.value), parseFloat(apresScolarite.value));
    });

    apresScolarite.addEventListener('input', () => {
        reductionScolarite.value = calculatePercentage1(parseFloat(avantScolarite.value), parseFloat(apresScolarite.value));
    });

    const avantArriere = document.getElementById('avantArrieremodif');
    const apresArriere = document.getElementById('apresArrieremodif');
    const reductionArriere = document.getElementById('modalReductionArriere');

    avantArriere.addEventListener('input', () => {
        reductionArriere.value = calculatePercentage(parseFloat(avantArriere.value), parseFloat(apresArriere.value));
    });

    apresArriere.addEventListener('input', () => {
        reductionArriere.value = calculatePercentage(parseFloat(avantArriere.value), parseFloat(apresArriere.value));
    });

    const avantFrais1 = document.getElementById('avantFrais1modif');
    const apresFrais1 = document.getElementById('apresFrais1modif');
    const reductionFrais1 = document.getElementById('modalReductionFrais1');

    avantFrais1.addEventListener('input', () => {
        reductionFrais1.value = calculatePercentage(parseFloat(avantFrais1.value), parseFloat(apresFrais1.value));
    });

    apresFrais1.addEventListener('input', () => {
        reductionFrais1.value = calculatePercentage(parseFloat(avantFrais1.value), parseFloat(apresFrais1.value));
    });

    const avantFrais2 = document.getElementById('avantFrais2modif');
    const apresFrais2 = document.getElementById('apresFrais2modif');
    const reductionFrais2 = document.getElementById('modalReductionFrais2');

    avantFrais2.addEventListener('input', () => {
        reductionFrais2.value = calculatePercentage(parseFloat(avantFrais2.value), parseFloat(apresFrais2.value));
    });

    apresFrais2.addEventListener('input', () => {
        reductionFrais2.value = calculatePercentage(parseFloat(avantFrais2.value), parseFloat(apresFrais2.value));
    });

    const avantFrais3 = document.getElementById('avantFrais3modif');
    const apresFrais3 = document.getElementById('apresFrais3modif');
    const reductionFrais3 = document.getElementById('modalReductionFrais3');

    avantFrais3.addEventListener('input', () => {
        reductionFrais3.value = calculatePercentage(parseFloat(avantFrais3.value), parseFloat(apresFrais3.value));
    });

    apresFrais3.addEventListener('input', () => {
        reductionFrais3.value = calculatePercentage(parseFloat(avantFrais3.value), parseFloat(apresFrais3.value));
    });

    const avantFrais4 = document.getElementById('avantFrais4modif');
    const apresFrais4 = document.getElementById('apresFrais4modif');
    const reductionFrais4 = document.getElementById('modalReductionFrais4');

    avantFrais4.addEventListener('input', () => {
        reductionFrais4.value = calculatePercentage(parseFloat(avantFrais4.value), parseFloat(apresFrais4.value));
    });

    apresFrais4.addEventListener('input', () => {
        reductionFrais4.value = calculatePercentage(parseFloat(avantFrais4.value), parseFloat(apresFrais4.value));
    });
});


 