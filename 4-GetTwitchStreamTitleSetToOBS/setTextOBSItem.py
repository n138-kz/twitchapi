import os
os.environ.pop('SSLKEYLOGFILE', None)

from obsws_python import ReqClient
import asyncio
import json

# .secretファイルからクライアントIDとシークレットキーを読み込む
with open('.secret', encoding='utf-8') as f:
    secret = json.load(f)

async def obs_example():
    client = ReqClient(
        host=secret['obs']['host'],
        port=secret['obs']['port'],
        password=secret['obs']['password']
    )

    response = client.set_input_settings(
        name=source_name,
        settings={"text": new_text},
        overlay=True # Trueにすると既存の設定を維持しつつ指定項目だけ上書き
    )

    print(f"テキストを更新しました: {response.status}")

source_name = secret['obs']['source_name']
new_text = "Pythonから書き換えたテキストです！"

if __name__ == "__main__":
    asyncio.run(obs_example())
