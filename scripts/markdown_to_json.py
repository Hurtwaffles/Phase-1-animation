import json, markdown, os, re, sys, pathlib

ROOT = pathlib.Path(__file__).resolve().parent.parent
DOCS = ROOT / "docs" / "markdown"
DATA = ROOT / "data" / "json"

def parse_bullets(md_path, pattern):
    text = md_path.read_text(encoding="utf-8")
    matches = re.findall(pattern, text)
    return matches

def sync_known_issues():
    issues = parse_bullets(DOCS / "ai_agent_known_issues.md", r"\*\*ISS-(\d{3})\*\*[^\n]*")
    data = []
    for num in issues:
        data.append({"id": f"ISS-{num}", "title": f"Placeholder issue {int(num)}", "description": ""})
    (DATA / "known_issues.json").write_text(json.dumps(data, indent=2))

if __name__ == "__main__":
    sync_known_issues()
    print("Sync complete.")