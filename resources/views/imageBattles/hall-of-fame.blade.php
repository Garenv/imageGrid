<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Hall of Fame</title>
    @vite(['resources/sass/imageBattles/hallOfFame.scss'])
</head>
<body>

<article class="images-sec-wrap">

    <div class="images-sec">
        <h1 class="text-center"><u>Image Battles Hall of Fame</u></h1>

        <ul class="images-sec-middle" id="vid-grid">

            @if(isset($hallOfFameInductees) && !$hallOfFameInductees->isEmpty())
                @foreach($hallOfFameInductees as $hallOfFameInductee)
                    <li class="thumb-wrap">
                        <img class="thumb" alt="photo" src={{ $hallOfFameInductee->url }} />
                        <div class="thumb-info text-center">
                            <h2 class="thumb-title">{{ $hallOfFameInductee->name }}</h2>
                            <h2 class="thumb-title text-black"><u>Prompt</u>: {{ $hallOfFameInductee->prompt }}</h2>
                            <h2 class="thumb-text">{{ $hallOfFameInductee->votes }} votes</h2>
                        </div>
                    </li>
                @endforeach
            @else
                <div class="middle-of-screen">No Hall of Fame inductees, yet!</div>
            @endif
        </ul>
    </div>
</article>

<script>
    let thumbTitle = document.querySelectorAll(".thumb-title");

    for(let i = 0; i < thumbTitle.length; i++) {

        if(thumbTitle[i].innerHTML.length > 50) {
            let shortenedTitle = thumbTitle[i].innerHTML.slice(0, 50);
            thumbTitle[i].innerHTML = shortenedTitle + "...";
        }

    }

</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
