<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegisterOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    // 1. Khai báo biến chứa mã OTP
    public $otp;

    /**
     * Create a new message instance.
     */
    public function __construct($otp)
    {
        // 2. Nhận biến OTP từ Controller truyền vào
        $this->otp = $otp;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            // 3. Đổi tiêu đề Mail cho xịn
            subject: 'Mã xác nhận đăng ký tài khoản', 
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            // 4. Dùng htmlString để nhúng trực tiếp HTML vào đây, rất tiện!
            htmlString: '
                <div style="font-family: sans-serif; line-height: 1.6; color: #333;">
                    <h2 style="color: #2563eb;">Xác nhận địa chỉ email</h2>
                    <p>Xin chào,</p>
                    <p>Bạn đang thực hiện đăng ký tài khoản mới. Mã xác nhận (OTP) của bạn là:</p>
                    <div style="background-color: #f3f4f6; padding: 15px; border-radius: 8px; text-align: center; margin: 20px 0;">
                        <span style="font-size: 32px; font-weight: bold; color: #1d4ed8; letter-spacing: 5px;">' . $this->otp . '</span>
                    </div>
                    <p style="color: #ef4444; font-size: 14px;"><i>*Lưu ý: Mã này chỉ có hiệu lực trong vòng 5 phút. Vui lòng không chia sẻ mã này cho bất kỳ ai.</i></p>
                    <hr style="border: 0; border-top: 1px solid #eee; margin-top: 30px;">
                    <p style="font-size: 12px; color: #999;">Đây là email tự động, vui lòng không trả lời email này.</p>
                </div>
            ',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}