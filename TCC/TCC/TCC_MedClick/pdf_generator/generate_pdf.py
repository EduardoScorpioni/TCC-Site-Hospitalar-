# generate_pdf.py - Versão completa e estilizada
import sys
import json
import os
from datetime import datetime
from reportlab.lib.pagesizes import A4
from reportlab.platypus import SimpleDocTemplate, Paragraph, Spacer, Image, Table, TableStyle
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.lib import colors

def main():
    if len(sys.argv) < 2:
        print("Erro: JSON não fornecido", file=sys.stderr)
        sys.exit(1)

    json_file = sys.argv[1]

    try:
        with open(json_file, "r", encoding="utf-8") as f:
            data = json.load(f)
    except Exception as e:
        print("Erro lendo JSON:", e, file=sys.stderr)
        sys.exit(1)

    # Define pasta de saída
    output_dir = os.path.join(os.path.dirname(__file__), "pdfs")
    if not os.path.exists(output_dir):
        os.makedirs(output_dir)

    # Nome do arquivo
    filename = "{}_{}_{}.pdf".format(
        data.get("tipo", "documento"),
        data.get("paciente_id", "0"),
        data.get("timestamp", "0000")
    )
    pdf_path = os.path.join(output_dir, filename)

    # Estilos
    styles = getSampleStyleSheet()
    title_style = ParagraphStyle(
        'TitleStyle',
        parent=styles['Title'],
        fontSize=16,
        textColor=colors.HexColor("#1a365d"),
        alignment=1,
        spaceAfter=20
    )
    normal = styles["Normal"]
    bold = ParagraphStyle('Bold', parent=normal, fontName="Helvetica-Bold")
    centered = ParagraphStyle('Centered', parent=normal, alignment=1)
    centered_bold = ParagraphStyle('CenteredBold', parent=bold, alignment=1)

    elements = []

    # Logo (opcional)
    try:
        logo_path = os.path.join(os.path.dirname(__file__), "logo.png")
        if os.path.exists(logo_path):
            img = Image(logo_path, width=100, height=100)
            img.hAlign = 'CENTER'
            elements.append(img)
    except:
        pass

    # Título baseado no tipo
    tipo = data.get("tipo", "comprovante")
    
    if tipo == "comprovante":
        titulo = "COMPROVANTE DE CONSULTA MÉDICA"
    elif tipo == "atestado":
        titulo = "ATESTADO MÉDICO"
    elif tipo == "receita":
        titulo = "RECEITA MÉDICA"
    else:
        titulo = "DOCUMENTO MÉDICO"

    elements.append(Paragraph(titulo, title_style))
    elements.append(Spacer(1, 15))

    # Dados principais em tabela
    dados = [
        ["Paciente:", data.get("paciente", "")],
        ["Médico:", "{} (CRM: {})".format(data.get("medico", ""), data.get("crm", ""))],
        ["Data:", data.get("data", "")],
    ]
    
    # Adiciona especialidade apenas se existir
    if data.get("especialidade"):
        dados.insert(2, ["Especialidade:", data.get("especialidade", "")])
    
    # Adiciona hora apenas para comprovante
    if tipo == "comprovante":
        dados.append(["Hora:", data.get("hora", "")])

    tabela = Table(dados, colWidths=[120, 350])
    tabela.setStyle(TableStyle([
        ('BACKGROUND', (0, 0), (0, -1), colors.HexColor("#e2e8f0")),
        ('TEXTCOLOR', (0, 0), (0, -1), colors.black),
        ('ALIGN', (0, 0), (-1, -1), 'LEFT'),
        ('FONTNAME', (0, 0), (-1, -1), 'Helvetica'),
        ('FONTSIZE', (0, 0), (-1, -1), 11),
        ('BOTTOMPADDING', (0, 0), (-1, -1), 8),
        ('GRID', (0, 0), (-1, -1), 0.5, colors.grey),
    ]))
    elements.append(tabela)
    elements.append(Spacer(1, 20))

    # Conteúdo específico por tipo
    if tipo == "comprovante":
        elements.append(Paragraph("<b>Resumo da Consulta:</b>", bold))
        elements.append(Spacer(1, 6))
        elements.append(Paragraph(data.get("content", "Consulta realizada com sucesso."), normal))
    
    elif tipo == "atestado":
        # Texto do atestado com campos preenchíveis
        atestado_texto = [
            "Atesto para os devidos fins que <b>{}</b>,".format(data.get("paciente", "")),
            "portador(a) do documento R.G. ______________________,",
            "residente e domiciliado(a) em ______________________,",
            "esteve sob tratamento médico, necessitando de",
            "<b>{}</b>.".format(data.get("content", "repouso/afastamento das atividades")),
            "",
            "Para fins de direito e de acordo com a legislação vigente,",
            "o presente atestado é válido por ____ dias.",
            "",
            "Data: {}".format(data.get("data", "")),
            "",
            "Atenciosamente,"
        ]
        
        for linha in atestado_texto:
            elements.append(Paragraph(linha, normal))
    
    elif tipo == "receita":
        elements.append(Paragraph("<b>Uso Interno - Via Oral</b>", bold))
        elements.append(Spacer(1, 10))
        
        # Conteúdo da receita (medicamentos)
        receita_content = data.get("content", "").split('\n')
        for linha in receita_content:
            if linha.strip():
                elements.append(Paragraph("• " + linha, normal))
        
        elements.append(Spacer(1, 20))
        
        # Instruções
        elements.append(Paragraph("<b>Instruções:</b>", bold))
        instrucoes = [
            "• Siga rigorosamente a posologia indicada",
            "• Não interrompa o tratamento sem orientação médica",
            "• Em caso de dúvidas, retorne ao consultório"
        ]
        
        for instrucao in instrucoes:
            elements.append(Paragraph(instrucao, normal))

    elements.append(Spacer(1, 30))

    # Assinatura
    elements.append(Paragraph("__________________________________", centered))
    elements.append(Paragraph("<b>{}</b>".format(data.get("medico", "")), centered))
    elements.append(Paragraph("CRM: {}".format(data.get("crm", "")), centered))
    if data.get("especialidade"):
        elements.append(Paragraph(data.get("especialidade", ""), centered))
    
    # Data (para receita)
    if tipo == "receita":
        elements.append(Spacer(1, 10))
        elements.append(Paragraph("Data: {}".format(data.get("data", "")), normal))

    # Rodapé com informações de contato
    elements.append(Spacer(1, 40))
    elements.append(Paragraph("<font size=9>MedClick - Sistema de Gestão Médica</font>", centered))
    elements.append(Paragraph("<font size=9>Telefone: (18) 2101-7300 | www.medclick.com.br</font>", centered))

    # Gera o PDF
    try:
        doc = SimpleDocTemplate(pdf_path, pagesize=A4)
        doc.build(elements)
    except Exception as e:
        print("Erro ao gerar PDF:", e, file=sys.stderr)
        sys.exit(1)

    # Imprime o caminho absoluto do PDF
    print(os.path.abspath(pdf_path))

if __name__ == "__main__":
    main()