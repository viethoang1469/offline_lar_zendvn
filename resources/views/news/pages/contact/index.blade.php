@extends('news.main')

@section('content')
    <div class="section-category">
        @include('news.block.breadcrumb', ['item' => ['name' => 'Liên hệ']])
        <!-- Content Container -->
        <div class="content_container container_category">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-sm-12" style="margin-bottom: 20px; min-height: 400px">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3919.3258374505854!2d106.69256!3d10.786337!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x5781806fe59379f4!2zQ8O0bmcgVHkgQ-G7lSBQaOG6p24gTOG6rXAgVHLDrG5oIFplbmQgVmnhu4d0IE5hbQ!5e0!3m2!1svi!2sus!4v1606183073548!5m2!1svi!2sus" width="100%" height="100%" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        @include('news.templates.notify')
                        <h4>Gửi tin nhắn cho chúng tôi</h4>
                        <p style="line-height: 25px">Bạn chỉ đầy đủ thông tin cá nhân và vấn đề trao đổi với ZendVN vào form bên dưới, sau khi nhận được thông tin này chúng tôi sẽ liên hệ với các bạn trong thời gian sớm nhất.</p>
                        <form action="{{ route('contact/post_contact') }}" id="contact-form" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Họ tên:</label>
                                <input type="text" class="form-control" id="name" name="name">
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                            <div class="form-group">
                                <label for="phone">Số điện thoại:</label>
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>
                            <div class="form-group">
                                <label for="message">Lời nhắn:</label>
                                <textarea name="message" id="message" class="form-control" rows="5"></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Gửi lời nhắn</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection