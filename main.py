"""Command line helper for writing TELC B2 letters.

This module provides ready-to-use letter examples together with
fill-in-the-blank (cloze) exercises tailored for Turkish speakers who are
learning German.  The templates focus on topics that commonly appear in TELC
B2 writing tasks and provide parallel Turkish explanations to help learners
understand the context.
"""

from __future__ import annotations

import argparse
import re
import textwrap
from dataclasses import dataclass
from typing import Dict, Iterable, List, Sequence, Tuple


def wrap_paragraphs(text: str, width: int = 78) -> str:
    """Wrap each paragraph separately to keep console output tidy."""

    lines: List[str] = []
    for paragraph in text.strip().split("\n\n"):
        lines.append(textwrap.fill(paragraph.strip(), width=width))
    return "\n\n".join(lines)


@dataclass(frozen=True)
class LetterTemplate:
    """Represents a TELC B2 letter scenario with bilingual guidance."""

    identifier: str
    title: str
    scenario_tr: str
    scenario_de: str
    letter_de: str
    letter_tr: str
    cloze_targets: Sequence[str]

    def formatted_example(self) -> str:
        """Return the formatted example letter with bilingual guidance."""

        parts = [
            f"Başlık / Thema: {self.title}",
            "\n--- Senaryo (Türkçe) ---\n" + wrap_paragraphs(self.scenario_tr),
            "\n--- Situation (Deutsch) ---\n" + wrap_paragraphs(self.scenario_de),
            "\n--- Mektup Örneği (Deutsch) ---\n" + wrap_paragraphs(self.letter_de),
            "\n--- Açıklama (Türkçe) ---\n" + wrap_paragraphs(self.letter_tr),
        ]
        return "\n".join(parts)

    def cloze_exercise(self) -> Tuple[str, Dict[int, str]]:
        """Create a cloze version of the German letter.

        Returns the cloze text and a dictionary mapping blank numbers to the
        original vocabulary items.
        """

        replacements: Dict[int, str] = {}
        pattern = re.compile(r"\b({})\b".format("|".join(map(re.escape, self.cloze_targets))), re.IGNORECASE)

        def replace(match: re.Match[str]) -> str:
            word = match.group(0)
            index = len(replacements) + 1
            replacements[index] = word
            return f"[{index}] " + "_" * max(6, len(word))

        cloze_text = pattern.sub(replace, self.letter_de)
        return wrap_paragraphs(cloze_text), replacements


TEMPLATES: Tuple[LetterTemplate, ...] = (
    LetterTemplate(
        identifier="kurs",
        title="Kursa şikayet / Beschwerde über einen Kurs",
        scenario_tr=(
            "İki aydır katıldığınız akşam Almanca kursunda son zamanlarda birçok sorun "
            "yaşanıyor: dersler sık sık iptal ediliyor, materyaller geç geliyor ve kurs "
            "müdürü sorularınıza cevap vermiyor. Kurs yöneticisine resmi bir mektup "
            "yazarak durumdan şikayetçi olun ve çözüm önerileri sunun."
        ),
        scenario_de=(
            "Seit zwei Monaten besuchen Sie einen Abendkurs. In letzter Zeit wird der "
            "Unterricht häufig abgesagt, die Materialien kommen verspätet und auf Ihre "
            "Fragen erhalten Sie keine Antworten. Schreiben Sie einen formellen Brief an "
            "die Kursleitung."
        ),
        letter_de=(
            "Sehr geehrte Frau Keller,\n\n"
            "seit zwei Monaten nehme ich an Ihrem Abendkurs \"Deutsch für den Beruf\" "
            "teil. Leider muss ich Ihnen mitteilen, dass es in den letzten Wochen zu "
            "mehreren Schwierigkeiten gekommen ist, die den Lernerfolg deutlich "
            "beeinträchtigen.\n\n"
            "Erstens fällt der Unterricht derzeit fast jede Woche mindestens einmal aus, "
            "weil die Lehrkraft kurzfristig absagt. Dadurch verlieren wir wertvolle Zeit "
            "und können die geplanten Inhalte nicht bearbeiten. Zweitens werden die "
            "Arbeitsblätter häufig erst während der Stunde ausgeteilt, sodass wir uns "
            "nicht vorbereiten können. Schließlich habe ich auf meine E-Mail mit Fragen "
            "zum Zertifikat bis heute keine Antwort erhalten.\n\n"
            "Ich bitte Sie daher, eine Vertretung zu organisieren, wenn die Lehrkraft "
            "verhindert ist, und die Unterrichtsmaterialien spätestens am Vortag "
            "bereitzustellen. Außerdem wäre ich Ihnen dankbar, wenn Sie mir zeitnah auf "
            "meine E-Mail antworten könnten.\n\n"
            "Ich hoffe auf eine schnelle Lösung und danke Ihnen im Voraus für Ihre "
            "Unterstützung.\n\n"
            "Mit freundlichen Grüßen\n\n"
            "Ayşe Yılmaz"
        ),
        letter_tr=(
            "Giriş paragrafında kursun adı belirtiliyor ve yaşanan sorunlar kısa bir "
            "özetle anlatılıyor. Gelişme bölümünde üç ayrı problem (derslerin iptali, "
            "geç gelen materyaller, yanıtsız e-posta) örneklerle açıklanıyor. Son bölüm "
            "ise çözüm beklentilerini ve teşekkür ifadesini içeriyor. Bu yapı, TELC B2 "
            "mektuplarında beklenen giriş-gelişme-sonuç düzenine uygundur."
        ),
        cloze_targets=(
            "Abendkurs",
            "Schwierigkeiten",
            "Unterricht",
            "Arbeitsblätter",
            "E-Mail",
            "Vertretung",
            "Unterstützung",
        ),
    ),
    LetterTemplate(
        identifier="einladung",
        title="Etkinliğe davet / Einladung zu einer Veranstaltung",
        scenario_tr=(
            "Mahallenizde kültürlerarası bir akşam düzenleniyor. Organizasyon ekibindesiniz "
            "ve B2 sınavındaki görev gibi bir arkadaşınızı resmi olarak davet etmeniz "
            "gerekiyor. Tarih, program ve katkı isteğini açıklamalısınız."
        ),
        scenario_de=(
            "In Ihrem Stadtteil findet ein interkultureller Abend statt. Sie sind im "
            "Organisationsteam und möchten eine Bekannte offiziell einladen. Nennen Sie "
            "Datum, Programm und bitten Sie um Unterstützung."
        ),
        letter_de=(
            "Liebe Frau Demir,\n\n"
            "am Samstag, den 18. Mai, organisieren wir im Nachbarschaftszentrum einen "
            "interkulturellen Abend. Sehr gern würde ich Sie zu dieser besonderen "
            "Veranstaltung einladen.\n\n"
            "Ab 17 Uhr präsentieren verschiedene Nachbarn ihre Länder mit Musik, kurzen "
            "Vorträgen und typischen Speisen. Um 19 Uhr wollen wir gemeinsam ein Buffet "
            "eröffnen. Es wäre wunderbar, wenn Sie eine kleine Spezialität aus der "
            "türkischen Küche mitbringen könnten.\n\n"
            "Bitte geben Sie mir bis zum 5. Mai Bescheid, ob Sie kommen und ob ich Ihnen "
            "bei der Vorbereitung helfen darf. Ich freue mich auf einen schönen Abend "
            "mit Ihnen.\n\n"
            "Herzliche Grüße\n\n"
            "Mehmet Kaya"
        ),
        letter_tr=(
            "Mektup sıcak bir hitapla başlıyor, tarih ve etkinliğin yeri hemen "
            "paylaşılıyor. Ardından program adım adım anlatılıyor ve nezaketle bir katkı "
            "talebi yapılıyor. Son paragraf, cevap beklediğini ve birlikte vakit geçirmek "
            "istediğini belirtiyor; bu da davet mektupları için ideal bir kapanış."
        ),
        cloze_targets=(
            "interkulturellen",
            "Nachbarschaftszentrum",
            "Veranstaltung",
            "Buffet",
            "Spezialität",
            "Vorbereitung",
        ),
    ),
    LetterTemplate(
        identifier="bewerbung",
        title="İş başvurusu / Bewerbung um eine Stelle",
        scenario_tr=(
            "Bir dil okulunda yarı zamanlı ofis asistanı pozisyonu ilan edildi. Görevler "
            "arası öğrenci kayıtlarını düzenlemek, telefonlara cevap vermek ve etkinlikler "
            "planlamak var. TELC B2 sınavındaki gibi motivasyon mektubu yazın."
        ),
        scenario_de=(
            "Eine Sprachschule sucht eine Teilzeit-Büroassistenz. Zu den Aufgaben gehören "
            "die Organisation von Anmeldungen, Telefonservice und die Planung von "
            "Veranstaltungen. Verfassen Sie ein Motivationsschreiben."
        ),
        letter_de=(
            "Sehr geehrte Damen und Herren,\n\n"
            "mit großem Interesse habe ich Ihre Anzeige für die Teilzeitstelle als "
            "Büroassistenz gelesen. Gern möchte ich mich Ihnen als engagierte und "
            "zuverlässige Bewerberin vorstellen.\n\n"
            "Zurzeit studiere ich Internationale Kommunikation im vierten Semester und "
            "arbeite nebenbei in einem Callcenter. Dort habe ich gelernt, Anfragen "
            "freundlich und strukturiert zu bearbeiten. Außerdem organisiere ich im "
            "Studentenverein regelmäßig Informationsabende, wodurch mir die Planung von "
            "Veranstaltungen vertraut ist.\n\n"
            "Ich spreche fließend Deutsch, Türkisch und Englisch und kenne die Bedürfnisse "
            "internationaler Studierender sehr gut. Ich bin überzeugt, dass ich Ihr Team "
            "mit meiner offenen Art unterstützen kann. Über eine Einladung zu einem "
            "persönlichen Gespräch würde ich mich sehr freuen.\n\n"
            "Mit freundlichen Grüßen\n\n"
            "Elif Arslan"
        ),
        letter_tr=(
            "Başvurunun giriş kısmı ilana atıfta bulunuyor ve kendini tanıtıyor. Orta "
            "paragrafta eğitim ve iş deneyimi, görevle bağlantılı örneklerle açıklanıyor. "
            "Dil bilgileri ve motivasyon vurgulanıyor. Son cümle görüşme isteğini nazikçe "
            "belirtiyor; bu, resmi başvurular için beklenen kapanıştır."
        ),
        cloze_targets=(
            "Teilzeitstelle",
            "engagierte",
            "Callcenter",
            "Informationsabende",
            "Veranstaltungen",
            "internationaler",
        ),
    ),
)


def list_templates(templates: Iterable[LetterTemplate]) -> str:
    """Return a numbered overview of the available letter templates."""

    lines = ["Mevcut mektup şablonları:"]
    for index, template in enumerate(templates, start=1):
        lines.append(f"  {index}. ({template.identifier}) {template.title}")
    return "\n".join(lines)


def show_example(template: LetterTemplate) -> str:
    """Return the bilingual letter example for the selected template."""

    return template.formatted_example()


def show_cloze(template: LetterTemplate) -> str:
    """Return the cloze exercise and answer key."""

    cloze_text, answers = template.cloze_exercise()
    answer_lines = ["\n--- Kelime Listesi / Lösungsschlüssel ---"]
    for index, word in answers.items():
        answer_lines.append(f"[{index}] {word}")
    return cloze_text + "\n" + "\n".join(answer_lines)


def parse_arguments() -> argparse.Namespace:
    parser = argparse.ArgumentParser(
        description=(
            "TELC B2 mektup çalışma yardımcısı. Şabloları listeleyebilir, örnek mektup "
            "görüntüleyebilir veya boşluk doldurma egzersizini alabilirsiniz."
        )
    )
    parser.add_argument(
        "identifier",
        nargs="?",
        help="Görüntülenecek mektup şablonunun kısa kodu (ör. 'kurs').",
    )
    parser.add_argument(
        "--mode",
        choices=("example", "cloze", "both"),
        default="both",
        help="Hangi içeriğin gösterileceğini seçin.",
    )
    parser.add_argument(
        "--list",
        action="store_true",
        help="Tüm şabloları listeleyin ve çıkın.",
    )
    return parser.parse_args()


def get_template_by_identifier(identifier: str) -> LetterTemplate:
    for template in TEMPLATES:
        if template.identifier == identifier:
            return template
    raise KeyError(f"'{identifier}' koduna sahip bir şablon bulunamadı.")


def main(argv: Sequence[str] | None = None) -> None:
    args = parse_arguments()

    if args.list or not args.identifier:
        print(list_templates(TEMPLATES))
        if not args.identifier:
            print("\nBir şablon görüntülemek için kodunu komut satırına ekleyin. Örnek:"
                  "\n  python -m text.py.main kurs --mode example")
        return

    try:
        template = get_template_by_identifier(args.identifier)
    except KeyError as error:
        print(error)
        print("\nGeçerli kodlar için --list parametresini kullanabilirsiniz.")
        return

    outputs: List[str] = []
    if args.mode in ("example", "both"):
        outputs.append(show_example(template))
    if args.mode in ("cloze", "both"):
        outputs.append(show_cloze(template))

    print("\n\n".join(outputs))


if __name__ == "__main__":
    main()
