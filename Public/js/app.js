
    $(document).ready(() => {

        let config = {
            type: 'line',
            data: {
                labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                    'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                datasets: []
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: ''
                }
            }
        };

        $.post("/", {}, (response, status) => {
            if(status === 'success') {
                let count = 0;
                let configs = [];

                Object.keys(response).forEach(title => {
                    config.options.title.text = title;
                    config.data.datasets = [];
                    count++;

                    Object.values(response[title]).forEach((value) => {
                        config.data.datasets.push({
                            label: value.label,
                            backgroundColor: value.backgroundColor,
                            borderColor: value.borderColor,
                            fill: false,
                            data: value.data
                        });
                    });

                    $('#my-chart-canvas').append(`<canvas id="canvas_${count}"> </canvas> <br/> <hr/>`);
                    configs[count] = JSON.parse(JSON.stringify(config));
                });

                while (count > 0){
                    new Chart(
                        document.getElementById(`canvas_${count}`).getContext('2d'),
                        configs[count]
                    );
                    count--;
                }
            }
        });
    });