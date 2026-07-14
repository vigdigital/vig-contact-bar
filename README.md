# 📞 VIG Contact Bar

**Thanh liên hệ nổi giúp khách kết nối với bạn chỉ bằng một chạm — cài xong là chạy, không cần biết code.**

Bạn có đang mất khách chỉ vì họ không tìm thấy nút liên hệ? VIG Contact Bar gắn một thanh liên hệ gọn gàng, luôn hiện ở góc màn hình để khách **chat, nhắn tin hoặc gửi form** cho bạn ngay lập tức — trên cả máy tính lẫn điện thoại.

## Vì sao nên dùng

- 💬 **Khách liên hệ ngay** — không phải cuộn tìm số điện thoại.
- 📱 **Đẹp sẵn trên điện thoại** — tự co giãn, không vỡ giao diện.
- 🇻🇳 **Tiếng Việt sẵn** — hỗ trợ cả website đa ngôn ngữ (Polylang).
- ⚡ **Nhẹ nhàng** — không làm chậm website.

## Có sẵn những gì

- Chat trực tiếp qua **Tawk.to** (miễn phí)
- Nút nhắn **WhatsApp**
- **Form liên hệ** (dán shortcode form bạn đang dùng)
- Thu gọn / mở rộng, ẩn chữ tuỳ ý cho gọn màn hình

## Cài đặt trong 2 phút

1. Tải file cài đặt mới nhất ở trang [Releases](../../releases).
2. Vào **WordPress → Plugins → Cài mới → Tải plugin lên**, chọn file vừa tải → **Cài đặt** → **Kích hoạt**.
3. Vào **VIG Toolkit → Contact Bar**, điền số điện thoại / link chat của bạn → bấm **Lưu**.

Xong! Thanh liên hệ hiện ngay trên website. **Không cần đụng tới một dòng code nào.**

## Cập nhật

Khi có phiên bản mới, WordPress sẽ tự báo — bạn chỉ cần bấm **Cập nhật** như mọi plugin khác. Không phải tải lại thủ công.

## Cần hỗ trợ?

VIG Contact Bar được phát triển & đồng hành bởi **[VIG Digital](https://vigdigital.com)**. Gặp vướng mắc hay muốn thêm tính năng? Cứ nhắn cho tụi mình một tiếng. 🙌

---

<details>
<summary><b>Dành cho developer / maintainer</b></summary>

- **Yêu cầu:** WordPress 6.0+, PHP 7.4+. Tuỳ chọn: Polylang (đa ngôn ngữ), tài khoản Tawk.to (live chat).
- **Tự cập nhật:** dùng [Plugin Update Checker](https://github.com/YahnisElsts/plugin-update-checker) đọc GitHub Releases. Repo đang private → site cần được cấp quyền để nhận update (xử lý theo VIG internal ops notes, không đặt token trong repo).
- **Phát hành bản mới:** bump `Version:` trong `vig-contact-bar.php` → commit → `git tag v1.1.0 && git push origin v1.1.0`. GitHub Action (`.github/workflows/build-release.yml`) tự build zip sạch và đính vào Release.

GPL-2.0-or-later © VIG Digital

</details>
