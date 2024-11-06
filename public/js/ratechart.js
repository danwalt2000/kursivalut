document.addEventListener("DOMContentLoaded", () => {
    const ctx = document.getElementById('chart');
    const currencyLabel = document.getElementById('chart_currency');
    const chartButtons = document.querySelectorAll(".chart-button");
    const chartDropdowns = document.querySelectorAll(".chart-period");
    const chartNavs = document.querySelectorAll(".chart-button, .chart-period");
    const activeDropdown = document.querySelector(".chart-period_active");
    let currency = 'dollar';
    let timerange = 14;
    let chartCanvas = false;
    
    const getJson = (event = false)=>{
        if(event){
            let target = event.target;
            if(target.dataset.currency){
                chartButtons.forEach(button => button.classList.remove("chart-currency_active"))
                target.classList.add("chart-currency_active");
                currency = target.dataset.currency;
                currencyLabel.innerText = target.dataset.title;
            } else if(target.dataset.period){
                timerange = target.dataset.period;
                chartDropdowns.forEach(el=> el.classList.remove("chart-period_hidden"));
                target.classList.add("chart-period_hidden");
                activeDropdown.innerText = target.innerText;
                setTimeout(() => {
                    document.querySelector(".chart-list .dropdown-hidden").classList.remove("active")
                }, 10);
            }
        }
        let locale = window.showRates ? window.locale : 'donetsk';
        const url = `/api/rates?locale=${locale}&currency=${currency}&timerange=${timerange}`;
        const req = new XMLHttpRequest();
        req.onload = (e) => {
            const resp = JSON.parse(e.currentTarget.responseText);
            // разрешение графика в зависимости от периода
            const dimensities = {
                "7": 12,
                "14": 12,
                "30": 24,
                "180": 24,
                "365": 48
            }
            // дефолтное разрешение графика - 8 часов
            let dimensity = dimensities[timerange]; 
            const averages = [];

            const stocks = resp.filter( rate => rate.locale === "stock" ).reverse()
            const locales = resp.filter( rate => rate.locale !== "stock" ).reverse()

            locales.forEach( (rate, index) => {
                let closestStock = stocks.filter( st => st.time === rate.time)
                let st = 0;
                if(!closestStock.length){
                    index === 0 ? st = stocks[0].average : st = averages[averages.length - 1][1][1]
                } else{
                    st = closestStock[0].average
                }
                averages.push([ rate.time, [rate.average, st] ])
            });
            const indexes = [0];
            averages.forEach( (rate, index) =>{
                let lastIndex = indexes[indexes.length - 1];
                if(rate[0] - dimensity*60*60 >= averages[lastIndex][0]) indexes.push(index); 
            });
            const chart = [];
            indexes.forEach( idx => chart.push(averages[idx]) )
            
            const dataset = { "time": [], "locale": [], "stock": [] }
            chart.forEach(el =>{
                dataset.time.push(el[0]);
                dataset.locale.push(el[1][0]);
                dataset.stock.push(el[1][1]);
            })

            initChart(dataset);
            const hiddenChart = document.querySelector(".chart_hidden");
            if(hiddenChart) hiddenChart.classList.remove("chart_hidden");
        };
        req.open("GET", url);
        req.send();

    }

    const initChart = (dataset)=>{
        let labels = dataset.time.map( rate => {
            let date = new Date(rate*1000)
            var day = date.getDate();
            var month = date.toLocaleString("ru-ru", { month: "short" });;
            var hours = date.getHours();

            return day + ' ' + month;
        })

        if(chartCanvas) chartCanvas.destroy();

        chartCanvas = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: window.isMobileDevice ? 'нал.' : 'Наличный курс' + window.h1Keyword,
                    data: dataset.locale,
                    borderWidth: 2,
                    borderColor: "#51d1e0",
                    backgroundColor : "#51d1e0"
                },
                {
                    label: window.isMobileDevice ? 'биржа' : 'Биржевой курс',
                    data: dataset.stock,
                    borderWidth: 1,
                    borderColor: "#ffb1c1",
                    backgroundColor : "#ffb1c1",
                }]
            },
            options: {
                scales: {
                y: {
                    // beginAtZero: true
                }
                }
            }
        });
    }
    
    getJson();
    
    chartNavs.forEach(button => button.addEventListener("click", (e)=> getJson(e) ) );
})
