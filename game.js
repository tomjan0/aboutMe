const PUZZLE_DIFFICULTY = 2; /*plansza 4x4*/
const PUZZLE_HOVER_TINT = '#009900'; /*kolor tla puzzla doc.*/
const MISSING_PIECE_STYLE = 'white';
let _canvas;
let _stage;
let _img;
let _pieces;
let _puzzleWidth;
let _puzzleHeight;
let _pieceWidth;
let _pieceHeight;
let _currentPiece;
let _currentDropPiece = null;
let _mouse;


function init() {
    _img = new Image();
    _img.addEventListener('load', onImage);
    _img.src = "photos/wiewiorka.png";
    _img.scale = 0.5;
}

function onImage() {
    _pieceWidth = Math.floor(_img.width / PUZZLE_DIFFICULTY)
    _pieceHeight = Math.floor(_img.height / PUZZLE_DIFFICULTY)
    _puzzleWidth = _pieceWidth * PUZZLE_DIFFICULTY;
    _puzzleHeight = _pieceHeight * PUZZLE_DIFFICULTY;
    setCanvas();
    initPuzzle();
}

function setCanvas() {
    _canvas = document.getElementById('canvas');
    _stage = _canvas.getContext('2d');
    _canvas.width = _puzzleWidth;
    _canvas.height = _puzzleHeight;
    _canvas.style.border = "1px solid black";
}

function initPuzzle() { /*inicjalizacja pierwotna i na replay*/
    _pieces = [];
    _mouse = {x: 0, y: 0};
    _currentPiece = null; /*na wypadek replay*/
    _currentDropPiece = null; /*na wypadek replay*/
    _stage.drawImage(_img, 0, 0, _puzzleWidth, _puzzleHeight,
        0, 0, _puzzleWidth, _puzzleHeight);
    createTitle("Click to Start Puzzle");
    buildPieces();
}

function createTitle(msg) {
    _stage.fillStyle = "#000000";
    _stage.globalAlpha = .4;
    _stage.fillRect(100, _puzzleHeight - 40, _puzzleWidth - 200, 40);
    _stage.fillStyle = "#FFFFFF";
    _stage.globalAlpha = 1; /*˙zeby tekst nie był przezr.*/
    _stage.textAlign = "center";
    _stage.textBaseline = "middle";
    _stage.font = "20px Arial";
    _stage.fillText(msg, _puzzleWidth / 2, _puzzleHeight - 20);
}

function buildPieces() {
    var i;
    var piece;
    var xPos = 0;
    var yPos = 0;
    for (i = 0; i < PUZZLE_DIFFICULTY * PUZZLE_DIFFICULTY; i++) {
        piece = {};
        piece.init = i;
        piece.sx = xPos;
        piece.sy = yPos;
        if (i === 0) {
            _currentDropPiece = piece;
            piece.red = true;
        }
        _pieces.push(piece);
        xPos += _pieceWidth;
        if (xPos >= _puzzleWidth) {
            xPos = 0;
            yPos += _pieceHeight;
        }
    }
    document.onmousedown = shufflePuzzle;
}

function getInvCount(arr) {
    let inv_count = 0;
    for (let i = 0; i < arr.length - 1; i++) {
        for (let j = i + 1; j < arr.length; j++) {
            // count pairs(i, j) such that i appears
            // before j, but i > j.
            if (arr[j] && arr[i] && arr[i] > arr[j])
                inv_count++;
        }
    }
    return inv_count;
}


function findXPosition(pieces) {
    // start from bottom-right corner of matrix
    for (let i = pieces.length - 1; i >= 0; i--) {
        if (pieces[i].init === 1) {
            const pos = pieces.length - i - 1;
            return Math.floor(pos / PUZZLE_DIFFICULTY + 1);
        }
    }
}

// This function returns true if given
// instance of N*N - 1 puzzle is solvable
function isSolvable(pieces) {
    // Count inversions in given pieces
    let invCount = getInvCount(pieces);

    // If grid is odd, return true if inversion
    // count is even.
    if (PUZZLE_DIFFICULTY % 2 === 1)
        return invCount % 2 === 0;
    else     // grid is even
    {
        const pos = findXPosition(pieces);
        if (pos % 2 === 1)
            return invCount % 2 === 0;
        else
            return invCount % 2 === 1;
    }
}

function shuffleArray(a) {
    let solvable = false;
    [a[0], a[a.length - 1]] = [a[a.length - 1], a[0]];
    while (!solvable) {
        for (let i = a.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [a[i], a[j]] = [a[j], a[i]];
        }
        solvable = isSolvable(a);
        console.log(solvable);
    }
    return a;
}

function shufflePuzzle() {
    _pieces = shuffleArray(_pieces);
    _stage.clearRect(0, 0, _puzzleWidth, _puzzleHeight);
    let i;
    let piece;
    let xPos = 0;
    let yPos = 0;
    for (i = 0; i < _pieces.length; i++) {
        piece = _pieces[i];
        piece.xPos = xPos;
        piece.yPos = yPos;
        if (piece.red) {
            _stage.fillStyle = MISSING_PIECE_STYLE;
            _stage.fillRect(piece.xPos,
                piece.yPos, _pieceWidth,
                _pieceHeight);
        } else {
            piece.red = false;
            _stage.drawImage(_img, piece.sx, piece.sy, _pieceWidth, _pieceHeight, xPos, yPos, _pieceWidth, _pieceHeight);
        }
        _stage.strokeRect(xPos, yPos, _pieceWidth, _pieceHeight);
        xPos += _pieceWidth;
        if (xPos >= _puzzleWidth) {
            xPos = 0;
            yPos += _pieceHeight;
        }
    }

    document.onmousedown = onPuzzleClick;
    document.onmousemove = updatePuzzle;
}

function onPuzzleClick(e) {
    _mouse.x = e.layerX;
    _mouse.y = e.layerY;
    if (_mouse.x === e.x || _mouse.y === e.y) {
        return;
    }
    console.log(_mouse.x, _mouse.y);
    _currentPiece = checkPieceClicked();
    if (_currentPiece) {
        const xdiff = Math.abs(_currentPiece.xPos - _currentDropPiece.xPos);
        const ydiff = Math.abs(_currentPiece.yPos - _currentDropPiece.yPos);
        // console.log(_pieceWidth, _pieceHeight);
        // console.log(xdiff, ydiff);
        if ((xdiff === _pieceWidth && ydiff === 0) || (xdiff === 0 && ydiff === _pieceHeight)) {
            pieceDropped();
        }
    }
}

function checkPieceClicked() {
    var i;
    var piece;
    for (i = 0; i < _pieces.length; i++) {
        piece = _pieces[i];
        if (_mouse.x < piece.xPos || _mouse.x > (piece.xPos + _pieceWidth) || _mouse.y < piece.yPos || _mouse.y > (piece.yPos + _pieceHeight)) {
//PIECE NOT HIT
        } else {
            return piece;
        }
    }
    return null;
}

function updatePuzzle(e) {
    // _currentDropPiece = null;
    // if(e.layerX || e.layerX == 0){
    //     _mouse.x = e.layerX - _canvas.offsetLeft;
    //     _mouse.y = e.layerY - _canvas.offsetTop;
    // }
    // else if(e.offsetX || e.offsetX == 0){
    //     _mouse.x = e.offsetX - _canvas.offsetLeft;
    //     _mouse.y = e.offsetY - _canvas.offsetTop;
    // }
    _mouse.x = e.layerX;
    _mouse.y = e.layerY;
    if (_mouse.x === e.x || _mouse.y === e.y) {
        return;
    }
    _stage.clearRect(0, 0, _puzzleWidth, _puzzleHeight);
    var i;
    var piece;
    for (i = 0; i < _pieces.length; i++) {
        piece = _pieces[i];
        // if(piece == _currentPiece){
        //     continue;
        // }
        if (piece.red) {
            _stage.fillStyle = MISSING_PIECE_STYLE;
            _stage.fillRect(piece.xPos,
                piece.yPos, _pieceWidth,
                _pieceHeight);
        } else {
            _stage.drawImage(_img, piece.sx, piece.sy,
                _pieceWidth, _pieceHeight, piece.xPos, piece.yPos,
                _pieceWidth, _pieceHeight);
        }
        _stage.strokeRect(piece.xPos, piece.yPos, _pieceWidth,
            _pieceHeight);
        // if(_currentDropPiece == null){
        if (_mouse.x < piece.xPos || _mouse.x >
            (piece.xPos + _pieceWidth) || _mouse.y < piece.yPos
            || _mouse.y > (piece.yPos + _pieceHeight) || piece.red) {
//NOT OVER
        } else {
            // _currentDropPiece = piece;
            _stage.save();
            _stage.globalAlpha = .4;
            _stage.fillStyle = PUZZLE_HOVER_TINT;
            _stage.fillRect(piece.xPos,
                piece.yPos, _pieceWidth,
                _pieceHeight);
            _stage.restore();
        }
        // }
    }
    // _stage.save();
    // _stage.globalAlpha = .6;
    // _stage.drawImage(_img, _currentPiece.sx, _currentPiece.sy, _pieceWidth, _pieceHeight, _mouse.x - (_pieceWidth / 2), _mouse.y - (_pieceHeight / 2), _pieceWidth, _pieceHeight);
    // _stage.restore();
    // _stage.strokeRect( _mouse.x - (_pieceWidth / 2), _mouse.y - (_pieceHeight / 2), _pieceWidth,_pieceHeight);
}

function pieceDropped(e) {
    // document.onmousemove = null;
    // document.onmouseup = null;
    if (_currentDropPiece != null) {
        var tmp = {xPos: _currentPiece.xPos, yPos: _currentPiece.yPos};
        _currentPiece.xPos = _currentDropPiece.xPos;
        _currentPiece.yPos = _currentDropPiece.yPos;
        _currentPiece.red = false;
        _currentDropPiece.xPos = tmp.xPos;
        _currentDropPiece.yPos = tmp.yPos;
        _currentDropPiece.red = true;
    }
    resetPuzzleAndCheckWin();
}

function resetPuzzleAndCheckWin() {
    _stage.clearRect(0, 0, _puzzleWidth, _puzzleHeight);
    var gameWin = true;
    var i;
    var piece;
    for (i = 0; i < _pieces.length; i++) {
        piece = _pieces[i];
        if (piece.red) {
            _stage.fillStyle = MISSING_PIECE_STYLE;
            _stage.fillRect(piece.xPos,
                piece.yPos, _pieceWidth,
                _pieceHeight);
        } else {
            _stage.drawImage(_img, piece.sx, piece.sy,
                _pieceWidth, _pieceHeight, piece.xPos, piece.yPos,
                _pieceWidth, _pieceHeight);
        }
        _stage.strokeRect(piece.xPos, piece.yPos, _pieceWidth,
            _pieceHeight);
        if (piece.xPos !== piece.sx || piece.yPos !== piece.sy) {
            gameWin = false;
        }
    }
    if (gameWin) {
        setTimeout(gameOver, 500);
    }
}

function gameOver() {
    document.onmousedown = null;
    document.onmousemove = null;
    document.onmouseup = null;
    initPuzzle();
}
