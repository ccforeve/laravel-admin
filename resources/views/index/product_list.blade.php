@include('index._header')

<body id="index" class="flexv">
    <div class="flexitemv scroll main">
        <div>
            @if($type == 'free')
                <img src="/index_images/banner-user.jpg" width="100%">
            @elseif($type == 'experience')
                <img src="/index_images/nouser.jpg" width="100%">
            @endif
        </div>
        <div class="list">
            @foreach($products as $product)
                <div class="list__item">
                    <a href="{{ route('index.product_details', $product->id) }}" class="list__link">
                        <div class="flex center list__bg">
                            <img class="list__img" src="{{ config('app.index_image').$product->photo[0] }}">
                        </div>
                        <img class="list__logo" src="/index_images/logo.png">
                        <div class="flexv list__content">
                            <p class="list__name">{{ $product->name }}</p>
                            <div class="flex centerv list__details">
                                <div class="list__num">
                                    <p class="list__price">¥ 0.00</p>
                                    <p class="list__recive">{{ $product->buy_count }}人已领取</p>
                                </div>
                                @if($type == 'free')
                                    <p class="flex center list__btn">立即<br>领取</p>
                                @elseif($type == 'experience')
                                    <p class="flex center list__btn list__btn--default">立即<br>体验</p>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    @include('index._footer')

    @include('index._notice')

    <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="/plugins/mobile/layer.js"></script>
    <script src="/js/common.js"></script>
</body>
</html>