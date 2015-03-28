/*global $, baseUrl, jQuery, alert*/

var dataUni = [
        {
            value: 24,
            color: "#553100",
            highlight: "#FF5A5E",
            label: "Computer Science"
        },
        {
            value: 24,
            color: "#FFDBAA",
            highlight: "#5AD3D1",
            label: "Electronics"
        },
        {
            value: 19,
            color: "#D4A76A",
            highlight: "#FFC870",
            label: "Signal Theory"
        },
        {
            value: 14,
            color: "#805215",
            highlight: "#A8B3C5",
            label: "Physics"
        },
        {
            value: 14,
            color: "#AA7939",
            highlight: "#616774",
            label: "Maths"
        },
        {
            value: 5,
            color: "#272727",
            highlight: "#616774",
            label: "Other stuff"
        }
    ],
    dataProgLang = {
        labels: ["Java", "PHP", "Javascript", "HTML", "CSS", "MYSQL", "C#", 'C++', 'UML'],
        datasets: [
            {
                label: "Languages",
                fillColor: "rgba(220,220,220,0.2)",
                strokeColor: "rgba(220,220,220,1)",
                pointColor: "rgba(220,220,220,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data: [4, 3.5, 4, 4, 4, 3, 3, 2.5, 3.5]
            }
        ]
    },
    keyWords = [
        {text: "Java", weight: 4},
        {text: "PHP", weight: 3.5},
        {text: "Javascript", weight: 4},
        {text: "HTML", weight: 3},
        {text: "CSS", weight: 3},
        {text: "MySQL", weight: 3},
        {text: "C++", weight: 2.5},
        {text: "UML", weight: 4},
        {text: "TDD", weight: 2.7},
        {text: "Linux Admin", weight: 2.5},
        {text: "GIT", weight: 4},
        {text: "SVN", weight: 1.5},
        {text: "VIM", weight: 2},
        {text: "Gimp", weight: 1.5},
        {text: "WebSphere MQ", weight: 1},
        {text: "Shell Scripting", weight: 1},
        {text: "Perl", weight: 1},
        {text: "WebSphere MB", weight: 1.5},
        {text: "Inkscape", weight: 2.5},
        {text: "C#", weight: 3}
    ],
    dataWork = [
        {
            value: 60,
            color: "#553100",
            highlight: "#FF5A5E",
            label: "Software Developer"
        },
        {
            value: 20,
            color: "#FFDBAA",
            highlight: "#5AD3D1",
            label: "Software Integrator"
        },
        {
            value: 15,
            color: "#D4A76A",
            highlight: "#FFC870",
            label: "System Admin."
        },
        {
            value: 5,
            color: "#805215",
            highlight: "#A8B3C5",
            label: "System Tester"
        }
    ],
    dataSector = [
        {
            value: 20,
            color: "#553100",
            highlight: "#FF5A5E",
            label: "NLP"
        },
        {
            value: 20,
            color: "#FFDBAA",
            highlight: "#5AD3D1",
            label: "SOA"
        },
        {
            value: 20,
            color: "#D4A76A",
            highlight: "#FFC870",
            label: "Digital Signage"
        },
        {
            value: 20,
            color: "#805215",
            highlight: "#A8B3C5",
            label: "Vehicle Telematics"
        },
        {
            value: 20,
            color: "#AA7939",
            highlight: "#616774",
            label: "Others"
        }
    ],
    polarOptions = {
        //Boolean - Show a backdrop to the scale label
        scaleShowLabelBackdrop: true,
        //String - The colour of the label backdrop
        scaleBackdropColor: "rgba(255,255,255,0.75)",
        // Boolean - Whether the scale should begin at zero
        scaleBeginAtZero: true,
        //Number - The backdrop padding above & below the label in pixels
        scaleBackdropPaddingY: 2,
        //Number - The backdrop padding to the side of the label in pixels
        scaleBackdropPaddingX: 2,
        //Boolean - Show line for each value in the scale
        scaleShowLine: true,
        //Boolean - Stroke a line around each segment in the chart
        segmentShowStroke: true,
        //String - The colour of the stroke on each segement.
        segmentStrokeColor: "#fff",
        //Number - The width of the stroke value in pixels
        segmentStrokeWidth: 2,
        //Number - Amount of animation steps
        animationSteps: 100,
        //String - Animation easing effect.
        animationEasing: "easeOutBounce",
        //Boolean - Whether to animate the rotation of the chart
        animateRotate: true,
        //Boolean - Whether to animate scaling the chart from the centre
        animateScale: true,
        scaleShowLabels: true,
        // Boolean - If we should show the scale at all
        showScale: true,
        //String - A legend template
        legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
    },
    activeSection = 'life';

function backToCassetteCoach() {
    'use strict';
    showLoading();
    window.location = baseUrl + '/home';
}

function openCV() {
    'use strict';
    window.open("https://docs.google.com/file/d/0B2hOO8PX6CgfamxFMUo3SmF4S2M/edit");
}

function showLoading() {
    'use strict';
    $('.homePageWrapper *').fadeOut(300, function () {
        $('.loadingMain').show();
    });
}

function paintCharts() {
    'use strict';
    // Get the context of the canvas element we want to select
    var ctx,
        uniChart,
        myRadarChart,
        workChart,
        sectorChart;
    //
    ctx = document.getElementById("uniChart").getContext("2d");
    uniChart = new Chart(ctx).PolarArea(dataUni, polarOptions);
    $('#uniChartLegend').html(uniChart.generateLegend());
    //
    ctx = document.getElementById("workChart").getContext("2d");
    workChart = new Chart(ctx).PolarArea(dataWork, polarOptions);
    $('#workChartLegend').html(workChart.generateLegend());
    //
    ctx = document.getElementById("sectorChart").getContext("2d");
    sectorChart = new Chart(ctx).Doughnut(dataSector);
    $('#sectorChartLegend').html(sectorChart.generateLegend());

    $('#keyWords').jQCloud(keyWords, { autoResize: true,  delay: 320, colors: ["#FFDBAA", "#D4A76A", "#805215", "#AA7939"]});
    //
    /*ctx = document.getElementById("langsChart").getContext("2d");
     myRadarChart = new Chart(ctx).Radar(dataProgLang,{pointLabelFontSize: 20});*/
}


function showMyStory() {
    'use strict';
    if (activeSection === 'life') {
        return;
    }
    $('#selectorNeedle').removeClass();
    $('.sectionSelector.' + activeSection).removeClass('active');
    $('.' + activeSection + 'Section').fadeOut(300, function () {
        $('.lifeSection').fadeIn(400)
    });
    $('.sectionSelector.life').addClass('active');
    activeSection = 'life';
}

function showMyExperience() {
    'use strict';
    if (activeSection === 'experience') {
        return;
    }
    $('#selectorNeedle').removeClass();
    $('#selectorNeedle').addClass('position2');
    $('.sectionSelector.' + activeSection).removeClass('active');
    $('.' + activeSection + 'Section').fadeOut(300, function () {
        $('.experienceSection').fadeIn(400, function () {
            paintCharts();
        });
    });
    $('.sectionSelector.experience').addClass('active');
    activeSection = 'experience';
}

function showMyHobbies() {
    'use strict';
    if (activeSection === 'hobbies') {
        return;
    }
    $('#selectorNeedle').removeClass();
    $('#selectorNeedle').addClass('position3');
    $('.sectionSelector.' + activeSection).removeClass('active');
    $('.' + activeSection + 'Section').fadeOut(300, function () {
        $('.hobbiesSection').fadeIn(400);
    });
    $('.sectionSelector.hobbies').addClass('active');
    activeSection = 'hobbies';
}

$(window).ready(function () {
    'use strict';
//
});