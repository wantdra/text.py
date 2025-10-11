"""Simple portfolio website server.

Run `python main.py` and open http://localhost:8000 to view the site.
"""
from http.server import BaseHTTPRequestHandler, HTTPServer
from urllib.parse import urlparse

PORTFOLIO_HTML = """
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Portfolyo | Geliştirici</title>
    <style>
        :root {
            color-scheme: light dark;
            --bg: #0f172a;
            --card: #1e293b;
            --text: #e2e8f0;
            --accent: #38bdf8;
            --accent-dark: #0284c7;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "Segoe UI", system-ui, -apple-system, sans-serif;
            background: radial-gradient(circle at top left, #1e3a8a, #0f172a 55%);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            padding: 4rem 10vw 2rem;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
        }

        nav a.logo {
            font-weight: 700;
            font-size: 1.2rem;
            letter-spacing: 0.08em;
            color: var(--accent);
            text-decoration: none;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 1.5rem;
        }

        nav ul li a {
            color: inherit;
            text-decoration: none;
            font-weight: 500;
            opacity: 0.85;
            transition: opacity 0.2s ease, color 0.2s ease;
        }

        nav ul li a:hover {
            opacity: 1;
            color: var(--accent);
        }

        .hero {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 3rem;
            align-items: center;
        }

        .hero h1 {
            font-size: clamp(2.4rem, 4vw, 3.6rem);
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .btn {
            padding: 0.85rem 1.6rem;
            border-radius: 999px;
            border: 1px solid transparent;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            color: #0f172a;
            box-shadow: 0 10px 30px -12px rgba(56, 189, 248, 0.7);
        }

        .btn-secondary {
            background: transparent;
            color: var(--text);
            border-color: rgba(148, 163, 184, 0.4);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 35px -18px rgba(56, 189, 248, 0.8);
        }

        main {
            flex: 1;
        }

        section {
            padding: 4rem 10vw;
        }

        .section-title {
            font-size: 1.8rem;
            margin-bottom: 2.5rem;
            position: relative;
            display: inline-block;
        }

        .section-title::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -0.5rem;
            width: 45%;
            height: 3px;
            background: linear-gradient(135deg, var(--accent), transparent);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.75rem;
        }

        .card {
            background: rgba(15, 23, 42, 0.55);
            border: 1px solid rgba(148, 163, 184, 0.1);
            border-radius: 1.25rem;
            padding: 2rem;
            box-shadow: 0 30px 45px -35px rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(10px);
            transition: transform 0.2s ease, border-color 0.2s ease;
        }

        .card:hover {
            transform: translateY(-4px);
            border-color: rgba(56, 189, 248, 0.45);
        }

        .card h3 {
            margin-bottom: 0.75rem;
            font-size: 1.2rem;
        }

        .card p {
            line-height: 1.6;
            opacity: 0.85;
            font-size: 0.95rem;
        }

        .timeline {
            border-left: 2px solid rgba(148, 163, 184, 0.2);
            padding-left: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1.75rem;
        }

        .timeline-item {
            position: relative;
        }

        .timeline-item::before {
            content: "";
            position: absolute;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--accent);
            border: 2px solid rgba(15, 23, 42, 0.8);
            left: -23px;
            top: 0.35rem;
        }

        footer {
            padding: 2rem 10vw 3rem;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        footer a {
            color: var(--accent);
            text-decoration: none;
        }

        @media (max-width: 768px) {
            header,
            section,
            footer {
                padding-left: 7vw;
                padding-right: 7vw;
            }

            nav ul {
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <a class="logo" href="#">Geliştirici</a>
            <ul>
                <li><a href="#hakkimda">Hakkımda</a></li>
                <li><a href="#projeler">Projeler</a></li>
                <li><a href="#yetenekler">Yetenekler</a></li>
                <li><a href="#iletisim">İletişim</a></li>
            </ul>
        </nav>
        <div class="hero">
            <div>
                <h1>Merhaba, ben modern web deneyimleri tasarlayan bir geliştiriciyim.</h1>
                <p>
                    Kullanıcı odaklı arayüzler, performanslı sistemler ve ölçeklenebilir çözümler
                    oluşturmayı seviyorum. Haydi birlikte harika ürünler yapalım!
                </p>
                <div class="btn-group">
                    <a class="btn btn-primary" href="#projeler">Projelerimi İncele</a>
                    <a class="btn btn-secondary" href="#iletisim">İletişime Geç</a>
                </div>
            </div>
            <div class="card">
                <h3>Öne Çıkan Başarı</h3>
                <p>
                    2023 yılında kullanıcı etkileşimini %45 oranında artıran etkileşimli bir
                    analitik platformu geliştirdim. Tasarım ve kodun birlikteliğine inanıyorum.
                </p>
            </div>
        </div>
    </header>

    <main>
        <section id="hakkimda">
            <h2 class="section-title">Hakkımda</h2>
            <div class="grid">
                <div class="card">
                    <h3>Yaklaşımım</h3>
                    <p>
                        Her projeyi stratejik bir bakış açısıyla ele alıyorum. İş hedeflerini ve kullanıcı
                        ihtiyaçlarını anlamak, sürdürülebilir ve esnek çözümler üretmenin anahtarı.
                    </p>
                </div>
                <div class="card">
                    <h3>Deneyim</h3>
                    <p>
                        5+ yıllık deneyimim boyunca SaaS ürünleri, e-ticaret platformları ve veri görselleştirme
                        araçları geliştirdim. Modern JavaScript ekosistemine ve Python tabanlı servis mimarilerine hakîmim.
                    </p>
                </div>
                <div class="card">
                    <h3>Değerlerim</h3>
                    <p>
                        Şeffaflık, sürdürülebilirlik ve kaliteye odaklanırım. Kodun bakım yapılabilirliğini ve
                        ekibin iletişimini önceliklendirerek uzun soluklu başarıyı hedeflerim.
                    </p>
                </div>
            </div>
        </section>

        <section id="projeler">
            <h2 class="section-title">Seçili Projeler</h2>
            <div class="grid">
                <div class="card">
                    <h3>Nova Dashboard</h3>
                    <p>
                        Gerçek zamanlı veri akışını görselleştiren modüler bir analitik dashboard. React ve D3.js ile
                        geliştirildi, Python tabanlı mikro servisler tarafından besleniyor.
                    </p>
                </div>
                <div class="card">
                    <h3>Aether Commerce</h3>
                    <p>
                        Çok dilli e-ticaret deneyimleri için optimize edilmiş headless bir altyapı. Next.js ve GraphQL
                        ile inşa edildi, küresel CDN ile destekleniyor.
                    </p>
                </div>
                <div class="card">
                    <h3>Pulse AI Studio</h3>
                    <p>
                        Yapay zekâ odaklı içerik üretim platformu. Kullanıcı yolculuğu testleri sayesinde %60 daha hızlı
                        içerik üretimine olanak tanıdı.
                    </p>
                </div>
            </div>
        </section>

        <section id="yetenekler">
            <h2 class="section-title">Yetenekler</h2>
            <div class="grid">
                <div class="card">
                    <h3>Teknik</h3>
                    <p>
                        TypeScript, React, Next.js, Node.js, Python, FastAPI, PostgreSQL, Redis, Docker, Kubernetes
                    </p>
                </div>
                <div class="card">
                    <h3>Tasarım</h3>
                    <p>
                        UI/UX prensipleri, komponent tabanlı tasarım, etkileşimli prototipleme, tasarım sistemleri
                    </p>
                </div>
                <div class="card">
                    <h3>Çalışma Şekli</h3>
                    <p>
                        Çevik metodolojiler, çapraz disiplinli ekip liderliği, ürün keşfi, veri odaklı karar alma
                    </p>
                </div>
            </div>
        </section>

        <section id="iletisim">
            <h2 class="section-title">İletişime Geçelim</h2>
            <div class="grid">
                <div class="card">
                    <h3>Birlikte Çalışalım</h3>
                    <p>
                        Yeni projeler ve iş birlikleri için her zaman hevesliyim. Aklınızdaki fikri birkaç cümleyle
                        anlatın, birlikte neler yapabileceğimizi konuşalım.
                    </p>
                </div>
                <div class="card">
                    <h3>İletişim Kanalları</h3>
                    <p>
                        <strong>E-posta:</strong> sizin.adiniz@example.com<br />
                        <strong>LinkedIn:</strong> linkedin.com/in/sizinprofiliniz<br />
                        <strong>GitHub:</strong> github.com/sizinprofiliniz
                    </p>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <span>© 2024 Geliştirici. Tüm hakları saklıdır.</span>
        <span><a href="#">CV'yi indir</a> · <a href="mailto:sizin.adiniz@example.com">Mail gönder</a></span>
    </footer>
</body>
</html>
"""


class PortfolioRequestHandler(BaseHTTPRequestHandler):
    """Serves the portfolio page for incoming GET requests."""

    def do_GET(self) -> None:  # noqa: N802  (BaseHTTPRequestHandler interface)
        path = urlparse(self.path).path

        if path in {"/", "/index.html"}:
            self.send_response(200)
            self.send_header("Content-Type", "text/html; charset=utf-8")
            self.end_headers()
            self.wfile.write(PORTFOLIO_HTML.encode("utf-8"))
        else:
            self.send_error(404, "Sayfa bulunamadı")

    def log_message(self, format: str, *args: object) -> None:  # noqa: A003
        """Log messages to stdout in a cleaner format."""
        print(f"[HTTP] {self.address_string()} - {format % args}")


def run_server(port: int = 8000) -> None:
    """Start the HTTP server that hosts the portfolio site."""
    server_address = ("", port)
    httpd = HTTPServer(server_address, PortfolioRequestHandler)
    print(f"Portfolyo sitesi http://localhost:{port} adresinde yayında. (Ctrl+C ile durdurun)")
    try:
        httpd.serve_forever()
    except KeyboardInterrupt:
        print("\nSunucu kapatılıyor...")
    finally:
        httpd.server_close()


if __name__ == "__main__":
    run_server()
