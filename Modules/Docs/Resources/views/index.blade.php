@extends('docs::layouts.docs')

<style type="text/css">
    /*@import url("https://fonts.googleapis.com/css?family=Cardo:400i|Rubik:400,700&display=swap");*/
    :root {
        --d: 700ms;
        --e: cubic-bezier(0.19, 1, 0.22, 1);
        --font-sans: "Rubik", sans-serif;
        --font-serif: "Cardo", serif;
    }

    * {
        box-sizing: border-box;
    }

    /*body {*/
    /*    display: grid;*/
    /*    place-items: center;background-color: #1F1F1F;*/
    /*}*/

    .docs-list-content {
        display: grid;
        grid-gap: 1rem;
        padding: 10px 0;
        /*max-width: 1024px;*/
        width: 100%;
        margin: 0 auto;
        font-family: var(--font-sans);
    }
    @media (max-width: 767.99px) {
        .docs-list-content {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (min-width: 768px) {
        .docs-list-content {
            grid-template-columns: repeat(2, 1fr);
        }
        .hidden-menu + .docs-right-content > .docs-list-content {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    @media (min-width: 992px) {
        .docs-list-content {
            grid-template-columns: repeat(3, 1fr);
        }
        .hidden-menu + .docs-right-content > .docs-list-content {
            grid-template-columns: repeat(4, 1fr);
        }
    }
    @media (min-width: 1200px) {
        .docs-list-content {
            grid-template-columns: repeat(4, 1fr);
        }
        .hidden-menu + .docs-right-content > .docs-list-content {
            grid-template-columns: repeat(5, 1fr);
        }
    }
    @media (min-width: 1650px) {
        .docs-list-content {
            grid-template-columns: repeat(5, 1fr);
        }
        .hidden-menu + .docs-right-content > .docs-list-content {
            grid-template-columns: repeat(6, 1fr);
        }
    }

    .docs-card {
        position: relative;
        display: flex;
        align-items: flex-end;
        overflow: hidden;
        padding: 6px;
        width: 100%;
        text-align: center;
        color: whitesmoke;
        background-color: whitesmoke;
        /*box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1), 0 2px 2px rgba(0, 0, 0, 0.1), 0 4px 4px rgba(0, 0, 0, 0.1), 0 8px 8px rgba(0, 0, 0, 0.1), 0 16px 16px rgba(0, 0, 0, 0.1);*/

        box-shadow: 7px 10px 3px #333;
    }
    @media (max-width: 768px) {
        .docs-card {
            height: 260px;
        }
    }
    .docs-card:before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 110%;
        background-size: cover;
        background-position: 0 0;
        transition: transform calc(var(--d) * 1.5) var(--e);
        pointer-events: none;
    }
    .docs-card:after {
        content: "";
        display: block;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 200%;
        pointer-events: none;
        background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 0.009) 11.7%, rgba(0, 0, 0, 0.034) 22.1%, rgba(0, 0, 0, 0.072) 31.2%, rgba(0, 0, 0, 0.123) 39.4%, rgba(0, 0, 0, 0.182) 46.6%, rgba(0, 0, 0, 0.249) 53.1%, rgba(0, 0, 0, 0.32) 58.9%, rgba(0, 0, 0, 0.394) 64.3%, rgba(0, 0, 0, 0.468) 69.3%, rgba(0, 0, 0, 0.54) 74.1%, rgba(0, 0, 0, 0.607) 78.8%, rgba(0, 0, 0, 0.668) 83.6%, rgba(0, 0, 0, 0.721) 88.7%, rgba(0, 0, 0, 0.762) 94.1%, rgba(0, 0, 0, 0.79) 100%);
        transform: translateY(-50%);
        transition: transform calc(var(--d) * 2) var(--e);
    }
    .docs-card:nth-child(1):before {
        background-image: url({{ asset('static/images/docs/1003-236x350.jpg') }});
    }
    .docs-card:nth-child(2):before {
        background-image: url({{ asset('static/images/docs/1032-236x350.jpg') }});
    }
    .docs-card:nth-child(3):before {
        background-image: url({{ asset('static/images/docs/1041-236x350.jpg') }});
    }
    .docs-card:nth-child(4):before {
        background-image: url({{ asset('static/images/docs/1042-236x350.jpg') }});
    }

    .docs-content {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        padding: 8px;
        transition: transform var(--d) var(--e);
        z-index: 1;
    }
    /*.docs-content > * + * {*/
    /*    margin-top: 1rem;*/
    /*}*/

    .docs-card-title {
        font-size: 1.3rem;
        font-weight: bold;
        line-height: 1.2;

        overflow : hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .docs-card-copy {
        font-family: var(--font-serif);
        font-size: 1.125rem;
        font-style: italic;
        line-height: 1.35;

        overflow : hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
    }

    .docs-card-btn {
        cursor: pointer;
        margin-top: 1.5rem;
        padding: 0.75rem 1.5rem;
        font-size: 0.65rem;
        font-weight: bold;
        letter-spacing: 0.025rem;
        text-transform: uppercase;
        color: white;
        background-color: black;
        border: none;
    }
    .docs-card-btn:hover {
        background-color: #0d0d0d;
    }
    .docs-card-btn:focus {
        outline: 1px dashed yellow;
        outline-offset: 3px;
    }

    @media (hover: hover) and (min-width: 768px) {
        .docs-card:after {
            transform: translateY(0);
        }

        .docs-content {
            transform: translateY(calc(100% - 84px));
        }
        .docs-content > *:not(.docs-card-title) {
            opacity: 0;
            transform: translateY(1rem);
            transition: transform var(--d) var(--e), opacity var(--d) var(--e);
        }

        .docs-card:hover,
        .docs-card:focus-within {
            align-items: center;
        }
        .docs-card:hover:before,
        .docs-card:focus-within:before {
            transform: translateY(-4%);
        }
        .docs-card:hover:after,
        .docs-card:focus-within:after {
            transform: translateY(-50%);
        }
        .docs-card:hover .docs-content,
        .docs-card:focus-within .docs-content {
            transform: translateY(0);
        }
        .docs-card:hover .docs-content > *:not(.docs-card-title),
        .docs-card:focus-within .docs-content > *:not(.docs-card-title) {
            opacity: 1;
            transform: translateY(0);
            transition-delay: calc(var(--d) / 8);
        }

        .docs-card:focus-within:before, .docs-card:focus-within:after,
        .docs-card:focus-within .docs-content,
        .docs-card:focus-within .docs-content > *:not(.docs-card-title) {
            transition-duration: 0s;
        }
    }
</style>

@section('content')
    <div class="docs-list-content">
        <div class="docs-card" style="background-image: url({{ asset('static/images/docs/1003-236x350.jpg') }});">
            <div class="docs-content">
                <h2 class="docs-card-title">Mountain View</h2>
                <p class="docs-card-copy">Check out all of these gorgeous mountain trips with beautiful views of, you guessed it, the mountains</p>
                <button class="docs-card-btn">View Trips</button>
            </div>
        </div>
        <div class="docs-card" style="background-image: url({{ asset('static/images/docs/1003-236x350.jpg') }});">
            <div class="docs-content">
                <h2 class="docs-card-title">To The Beach</h2>
                <p class="docs-card-copy">Plan your next beach trip with these fabulous destinations</p>
                <button class="docs-card-btn">View Trips</button>
            </div>
        </div>
        <div class="docs-card" style="background-image: url({{ asset('static/images/docs/1003-236x350.jpg') }});">
            <div class="docs-content">
                <h2 class="docs-card-title">Desert Destinations</h2>
                <p class="docs-card-copy">It's the desert you've always dreamed of</p>
                <button class="docs-card-btn">Book Now</button>
            </div>
        </div>
        <div class="docs-card" style="background-image: url({{ asset('static/images/docs/1003-236x350.jpg') }});">
            <div class="docs-content">
                <h2 class="docs-card-title">Explore The Galaxy</h2>
                <p class="docs-card-copy">Seriously, straight up, just blast off into outer space today</p>
                <button class="docs-card-btn">Book Now</button>
            </div>
        </div>
        <div class="docs-card" style="background-image: url({{ asset('static/images/docs/1003-236x350.jpg') }});">
            <div class="docs-content">
                <h2 class="docs-card-title">Explore The Galaxy</h2>
                <p class="docs-card-copy">Seriously, straight up, just blast off into outer space today</p>
                <button class="docs-card-btn">Book Now</button>
            </div>
        </div>
        <div class="docs-card" style="background-image: url({{ asset('static/images/docs/1003-236x350.jpg') }});">
            <div class="docs-content">
                <h2 class="docs-card-title">Explore The Galaxy</h2>
                <p class="docs-card-copy">Seriously, straight up, just blast off into outer space today</p>
                <button class="docs-card-btn">Book Now</button>
            </div>
        </div>
    </div>
@endsection
