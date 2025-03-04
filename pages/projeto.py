import streamlit as st

# Título da página
st.title("Projeto inicial")

# Texto principal
st.write("""
         
Ultima atualização?
	1.	Agora o Sistema 2 pensa antes de perguntar!
	•	Antes de perguntar, ele tenta achar conceitos similares que já aprendeu.
	•	Se encontrar, ele tenta formular uma resposta com base nisso.
	•	Se não encontrar, aí sim faz perguntas para aprender.
         
Embreve irei explicar o porque de ter 2 sistemas (rapido e o "lento").
        
import sqlite3

# Criando o banco de memória se não existir
def criar_banco():
    conn = sqlite3.connect("memoria.db")
    cursor = conn.cursor()
    cursor.execute(""
        CREATE TABLE IF NOT EXISTS memoria (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            entrada TEXT UNIQUE,
            resposta TEXT
        )
    "")
    conn.commit()
    conn.close()

# Salvando conhecimento na memória
def salvar_na_memoria(entrada, resposta):
    conn = sqlite3.connect("memoria.db")
    cursor = conn.cursor()
    cursor.execute("INSERT OR REPLACE INTO memoria (entrada, resposta) VALUES (?, ?)", (entrada.lower(), resposta))
    conn.commit()
    conn.close()

# Recuperando resposta da memória
def recuperar_resposta(entrada):
    conn = sqlite3.connect("memoria.db")
    cursor = conn.cursor()
    cursor.execute("SELECT resposta FROM memoria WHERE entrada = ?", (entrada.lower(),))
    resultado = cursor.fetchone()
    conn.close()
    return resultado[0] if resultado else None

# Sistema 1: Processamento rápido
def sistema_1(entrada):
    resposta = recuperar_resposta(entrada)
    if resposta:
        return resposta, "Sistema 1"
    return None, "Ativar Sistema 2"

# Sistema 2: Reflexão, aprendizado e conexões entre conceitos
def sistema_2(entrada):
    # Tenta encontrar conceitos similares no banco de memória
    conn = sqlite3.connect("memoria.db")
    cursor = conn.cursor()
    cursor.execute("SELECT entrada, resposta FROM memoria")
    dados = cursor.fetchall()
    conn.close()

    conceitos_relacionados = [resposta for conceito, resposta in dados if conceito in entrada or entrada in conceito]

    if conceitos_relacionados:
        resposta_final = f"Ainda não sei exatamente, mas com base no que aprendi, pode estar relacionado a: {', '.join(conceitos_relacionados)}"
        return resposta_final, "Sistema 2"

    # Se não encontrar nada, faz uma pergunta para aprender
    pergunta = f"Pode me dar mais detalhes sobre '{entrada}'?"
    resposta_auxiliar = input(f"IA: {pergunta}\nVocê: ")

    # Aprende e armazena a nova informação
    salvar_na_memoria(entrada, resposta_auxiliar)
    return f"Entendi! Agora sei sobre '{entrada}'. Obrigado!", "Sistema 2"

# Processo de decisão
def processar_entrada(entrada):
    resposta, sistema = sistema_1(entrada)

    if resposta is None:  # Se o Sistema 1 não souber, ativa o Sistema 2
        resposta, sistema = sistema_2(entrada)

    return resposta, sistema

# Criar banco na primeira execução
criar_banco()

# Loop de interação
while True:
    entrada_usuario = input("Você: ")
    if entrada_usuario.lower() == "sair":
        break
    resposta_ia, sistema_ativado = processar_entrada(entrada_usuario)
    print(f"IA ({sistema_ativado}): {resposta_ia}")
""")