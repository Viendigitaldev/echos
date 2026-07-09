const tabButtons = document.querySelectorAll('.tab-btn');
const cards = document.querySelectorAll('.production-card');

let activeFilters = ['all'];

tabButtons.forEach(button => {

    button.addEventListener('click', function(){

        const filter = this.dataset.filter;

        if(filter === 'all'){

            activeFilters = ['all'];

            tabButtons.forEach(btn =>
                btn.classList.remove('active')
            );

            this.classList.add('active');
        }
        else{

            document.querySelector('[data-filter="all"]')
                .classList.remove('active');

            activeFilters =
                activeFilters.filter(item => item !== 'all');

            this.classList.toggle('active');

            if(this.classList.contains('active')){

                activeFilters.push(filter);

            }else{

                activeFilters =
                    activeFilters.filter(item => item !== filter);
            }

            if(activeFilters.length === 0){

                activeFilters = ['all'];

                document.querySelector('[data-filter="all"]')
                    .classList.add('active');
            }
        }

        cards.forEach(card => {

            if(activeFilters.includes('all')){

                card.style.display = 'block';

            }else{

                const category = card.dataset.category;

                if(activeFilters.includes(category)){

                    card.style.display = 'block';

                }else{

                    card.style.display = 'none';
                }
            }

        });

    });

});
