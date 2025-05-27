import pandas as pd

# Step 1: Load the Excel file
file_path = '431 та умумий.xlsx'  # Change this to the actual path of your Excel file
df = pd.read_excel(file_path)

# Optional: Rename columns to valid JSON keys (remove spaces, quotes, etc.)
df.columns = [str(col).strip().replace('"', '').replace('\n', '_').replace(' ', '_') for col in df.columns]

# Step 2: Convert the DataFrame to JSON
json_data = df.to_json(orient='records', force_ascii=False, indent=4)

# Step 3: Save JSON to file
with open('output.json', 'w', encoding='utf-8') as f:
    f.write(json_data)

print("✅ JSON saved to 'output.json'")
