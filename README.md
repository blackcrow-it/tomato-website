# Cài đặt
- Apache: trỏ về thư mục `public`
- DB: PostgreSQL
- Chạy migrate
- Đăng nhập vào quản trị bằng tài khoản `admin`, mật khẩu `123456`

# Script
## Deploy nhanh lên môi trường staging
- Merge code vào branch `develop`
- Chạy `./deploy_next.sh`

## Deploy nhanh lên môi trường production
- Merge code vào branch `master`
- Chạy `./deploy_serve.sh`

# Thông tin access các máy chủ
## Máy chủ staging
| Key | Value |
| --- | --- |
| URL | https://next.tomatoonline.edu.vn |
| Host | 103.56.158.35 |
| Port | 22 |
| User | root |
| Key | web-ssh-key-private.pem |

## Máy chủ web production
| Key | Value |
| --- | --- |
| URL | https://tomatoonline.edu.vn |
| Host | 103.56.158.84 |
| Port | 22 |
| User | root |
| Key | web-ssh-key-private.pem |

## Máy chủ database
*Liên hệ anh Công để truy cập vào bizfly dashboard lấy thông tin.*

# Tool transcode video
Git: https://github.com/mrcyclo/tomato-transcode
