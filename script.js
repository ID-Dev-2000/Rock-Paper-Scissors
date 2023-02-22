// win streaks
const currentWinStreak = document.getElementById('currentWinStreak')
const highestWinStreak = document.getElementById('highestWinStreak')

// game result
const gameResult = document.getElementById('gameResult')

// player buttons
const playerRock = document.getElementById('playerRock')
const playerPaper = document.getElementById('playerPaper')
const playerScissors = document.getElementById('playerScissors')

// enemy buttons - not clickable
const enemyRock = document.getElementById('enemyRock')
const enemyPaper = document.getElementById('enemyPaper')
const enemyScissors = document.getElementById('enemyScissors')

// leaderboard
const viewLeaderboard = document.getElementById('viewLeaderboard')
const loggedScore = document.getElementById('loggedScore')

// set up elements, arrays for event listeners
playerRock.value = 'rock'
playerPaper.value = 'paper'
playerScissors.value = 'scissors'

enemyRock.value = 'rock'
enemyPaper.value = 'paper'
enemyScissors.value = 'scissors'

playerButtonArray = [playerRock, playerPaper, playerScissors]
enemyArray = [enemyRock, enemyPaper, enemyScissors]

// score tracking variables based on login
if(loginAuth == true) {
    var currentWins = 0
    var highestWins = accountLoginWins
    loggedScore.innerHTML = "You: " + accountUsername + " | Wins: " + highestWins
} else {
    var currentWins = 0
    var highestWins = 0
    loggedScore.innerHTML = "You: N/A | Wins: N/A"
}

// main game functions
function handleGame(item) {
    let enemyValue = Math.floor((Math.random() * enemyArray.length))
    let buttonTextValue = 0

    // handle button colors, enable buttons
    function resetButtons() {
        setTimeout(function() {
            // iterates through each button in array, handles status for each button
            playerButtonArray.forEach(function(button) {
                // re-enable buttons
                button.style.pointerEvents = ''

                // reset player button
                button.style.backgroundColor = 'deepskyblue'
                enemyArray.forEach(function(enemy) {
                    enemy.backgroundColor = 'crimson'
                })

                // reset enemy item
                enemyArray[enemyValue].style.backgroundColor = 'crimson'

                // reset text for each user button, rock = 0, paper = 1, scissors = 2
                switch (buttonTextValue) {
                    case 0:
                        button.innerHTML = '&#x1F44A';
                        break;
                    case 1:
                        button.innerHTML = '&#x1F590';
                        break;
                    case 2:
                        button.innerHTML = '&#x270C';
                        break;
                }
                buttonTextValue++
            })
            gameResult.innerHTML = ''
        }, 800)
    }

    // updates view of enemy selection, disable buttons, delay until view reset
    playerButtonArray.forEach(function(button) {
        button.style.backgroundColor = 'lightgrey'
        button.style.pointerEvents = 'none'
        button.innerHTML = '&#x26D4'
    })

    // when enemy button in array is randomly selected, update css, background colour, button will be reset after
    enemyArray.forEach(function(enemyItem) {
        if(enemyItem.value == enemyArray[enemyValue].value) {
            let enemyItemDOMElement = document.getElementById(enemyItem.id)
            enemyItemDOMElement.style.backgroundColor = 'goldenrod'
        }
    })

    // win/loss/draw conditions
    // &#x2714 win, &#x274C lose, &#x3030 draw
    function handleRPS() {
        if(item.value == enemyArray[enemyValue].value) {
            // draw clause, values remain
            gameResult.innerHTML = '&#x3030'
        } else if(
            // win clause
            item.value == 'rock' && enemyArray[enemyValue].value == 'scissors' ||
            item.value == 'paper' && enemyArray[enemyValue].value == 'rock' ||
            item.value == 'scissors' && enemyArray[enemyValue].value == 'paper'
            ) {
            gameResult.innerHTML = '&#x2714'
            
            // handle highest win streak / record
            if(currentWins >= highestWins) {
                highestWins++

                // update score in DB if loginAuth true
                if(loginAuth == true) {
                    let postRequest = new XMLHttpRequest()

                    const params = {
                        username: `${accountUsername}`,
                        score: `${highestWins}`
                    }

                    postRequest.open('POST', 'php/postScore.php', true)
                    postRequest.setRequestHeader("Content-type", "application/json")
                    postRequest.send(JSON.stringify(params))

                }
                highestWinStreak.innerHTML = highestWins
            }

            loggedScore.innerHTML = "You: " + accountUsername + " | Wins: " + highestWins

            currentWins++
            currentWinStreak.innerHTML = currentWins
        } else if(
            // loss clause
            item.value == 'rock' && enemyArray[enemyValue].value == 'paper' ||
            item.value == 'paper' && enemyArray[enemyValue].value == 'scissors' ||
            item.value == 'scissors' && enemyArray[enemyValue].value == 'rock'
        ) {
            gameResult.innerHTML = '&#x274C'
            currentWins = 0
            currentWinStreak.innerHTML = currentWins
        }
    }

    // call win/loss/draw function
    handleRPS()

    // reset display
    resetButtons()

}

// event listener for each button
playerButtonArray.forEach(function(element) {
    element.addEventListener('click', function() {
        handleGame(this)
    })
})

// modal
const modalBackground = document.getElementById('modalBackground')
const modalExitButton = document.getElementById('modalExitButton')

viewLeaderboard.addEventListener('click', function() {
    // display modal
    if(modalBackground.style.display = "none") {
        modalBackground.style.display = "block"
    }

    // hide modal
    document.querySelector('body').addEventListener('click', function(click) {
        if(click.target == document.getElementById('modalBackground')) {
            modalBackground.style.display = "none"
        }
    })

    modalExitButton.addEventListener('click', function() {
        modalBackground.style.display = "none"
    })

})

const modalRefreshButton = document.getElementById('refreshLeaderboard')

modalRefreshButton.addEventListener('click', function() {
    // disable button to prevent too many requests to server
    modalRefreshButton.style.pointerEvents = 'none'
    modalRefreshButton.style.backgroundColor = 'lightgrey'
    
    let intervalCounter = 3
    modalRefreshButton.innerHTML = intervalCounter
    let intervalVariable
    if(intervalCounter > 0 ){ intervalVariable = setInterval(function() {
        intervalCounter--
        modalRefreshButton.innerHTML = intervalCounter
    }, 999) }

    // get request here, set timeout on button to limit number of requests
    let GETLeaderboardData = new XMLHttpRequest()

    // AJAX update leaderboard
    GETLeaderboardData.onreadystatechange = function() {
        if(GETLeaderboardData.readyState == XMLHttpRequest.DONE) {
            if(GETLeaderboardData.status == 200) {
                let allResponse = JSON.parse(GETLeaderboardData.responseText)
                for(i=1; i<6; i++) {
                    const leaderBoardItem = document.getElementById(`leaderBoard${i}`)
                    leaderBoardItem.innerHTML = `<b>#${i}</b>: ` + allResponse[i-1]['username'] + ` - ${allResponse[i-1]['highest_score']} WINS`
                }
            } else {
                console.log("GET request error")
            }
        }
    }

    GETLeaderboardData.open("GET", "php/getScore.php")
    GETLeaderboardData.send()

    // re-enable button after delay
    setTimeout(function() {
        modalRefreshButton.style.pointerEvents = ''
        modalRefreshButton.style.backgroundColor = 'darkseagreen'
        modalRefreshButton.innerHTML = 'CLICK TO REFRESH'
        clearInterval(intervalVariable)
    }, 3000)
})
