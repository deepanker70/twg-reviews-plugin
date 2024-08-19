jQuery(document).ready(function($) {
    document.addEventListener('DOMContentLoaded', function() {
    const prosTextarea = document.querySelector('.twg-pros-textarea');
    const consTextarea = document.querySelector('.twg-cons-textarea');
    const prosList = document.querySelector('.twg-pros-list');
    const consList = document.querySelector('.twg-cons-list');

    function updateList(textarea, list) {
        list.innerHTML = '';
        
        const items = textarea.value.trim().split('\n');

        items.forEach(item => {
            if (item.trim()) {
                const listItem = document.createElement('li');
                listItem.textContent = item.trim();
                list.appendChild(listItem);
            }
        });
    }

    if (prosTextarea) {
        prosTextarea.addEventListener('input', function() {
            updateList(prosTextarea, prosList);
        });

        updateList(prosTextarea, prosList);
    }

    if (consTextarea) {
        consTextarea.addEventListener('input', function() {
            updateList(consTextarea, consList);
        });

        updateList(consTextarea, consList);
    }
});

    
    $('#twg_star_rating_slider').slider({
        range: 'min',
        min: 0,
        max: 5,
        step: 0.5,
        value: parseFloat($('#twg_review_stars').val()),
        slide: function(event, ui) {
            $('#twg_review_stars').val(ui.value);
            $('#twg_star_rating_value').text(ui.value);
        }
    });
});
