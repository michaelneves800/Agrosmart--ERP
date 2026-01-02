from flask import Flask, request, jsonify
from flask_cors import CORS
from openai import OpenAI
import json
import os
from dotenv import load_dotenv
app = Flask(__name__)
CORS(app)
load_dotenv()
client = OpenAI(api_key=os.getenv("OPENAI_API_KEY"))
@app.route('/gerar-relatorio', methods=['POST'])
def gerar_relatorio():
    try:
        data = request.json
        print(f"Processando dados da fazenda: {data}")

        prompt = f"""
        Analise estrategicamente como um consultor agro sênior:
        Faturamento: R$ {data.get('faturamento')}
        Área: {data.get('area_total')} HA
        Estoque: {data.get('estoque')}

        Responda obrigatoriamente neste formato JSON:
        {{
          "financeiro": "Escreva aqui um parágrafo de análise financeira.",
          "manejo": "Escreva aqui um parágrafo de sugestões técnicas.",
          "melhorias": "Escreva aqui um parágrafo sobre estoque e melhorias."
        }}
        """

        response = client.chat.completions.create(
            model="gpt-3.5-turbo-0125",
            messages=[
                {"role": "system", "content": "Você fornece diagnósticos agrícolas diretos em formato JSON."},
                {"role": "user", "content": prompt}
            ],
            response_format={"type": "json_object"}
        )

        # Retorna a resposta limpa
        return jsonify({"relatorio": json.loads(response.choices[0].message.content)})

    except Exception as e:
        print(f"ERRO: {str(e)}")
        return jsonify({"error": str(e)}), 500
    
@app.route('/analisar-praga', methods=['POST'])
def analisar_praga():
    try:
        data = request.json
        sintomas = data.get('sintomas')
        insumos_estoque = data.get('insumos_estoque')

        prompt = f"""
        Como um especialista agrônomo, analise os seguintes sintomas da planta: "{sintomas}".
        Considere que o estoque atual de insumos é: {json.dumps(insumos_estoque)}.

        1. Identifique a praga ou doença mais provável.
        2. Recomende ações de manejo detalhadas.
        3. Com base no estoque fornecido, sugira qual insumo deve ser usado e qual a dosagem aproximada.
        4. Se não houver insumo adequado no estoque, sugira um genérico e uma observação.

        Responda EXATAMENTE neste formato JSON:
        {{
          "praga": "Nome da Praga/Doença",
          "recomendacao": "Detalhes sobre o manejo e combate.",
          "insumos_sugeridos": [
            {{"nome": "Nome Insumo", "quantidade_sugerida": "X Litros/KG", "detalhes": "Modo de aplicação"}}
          ],
          "observacao": "Alguma observação adicional, se necessário (ex: Sugerir compra de outro insumo)."
        }}
        """

        response = client.chat.completions.create(
            model="gpt-3.5-turbo-0125",
            messages=[
                {"role": "system", "content": "Você é um agrônomo especialista em diagnóstico de pragas."},
                {"role": "user", "content": prompt}
            ],
            response_format={"type": "json_object"}
        )

        diagnostico = json.loads(response.choices[0].message.content)
        return jsonify({"diagnostico": diagnostico})

    except Exception as e:
        print(f"ERRO ao analisar praga: {str(e)}")
        return jsonify({"error": str(e)}), 500 
    

@app.route('/ia/perguntar', methods=['POST'])
def chatbot():
    try:
        data = request.json
        pergunta = data.get('pergunta')
        
        # O AgroBot responde de forma amigável e técnica
        response = client.chat.completions.create(
            model="gpt-3.5-turbo",
            messages=[
                {"role": "system", "content": "Você é o AgroBot, um assistente técnico agrícola amigável."},
                {"role": "user", "content": pergunta}
            ]
        )
        
        return jsonify({"resposta": response.choices[0].message.content})
    except Exception as e:
        print(f"Erro no Chat: {str(e)}")
        return jsonify({"error": str(e)}), 500    

if __name__ == '__main__':
    app.run(port=5000, debug=True)