import pandas as pd
import json
import requests
import re
import time
from urllib.parse import urlparse, parse_qs

def extract_coordinates_from_google_maps(url):
    """
    Extract coordinates from Google Maps URL
    Supports various Google Maps URL formats
    """
    try:
        print(f"    🔍 Processing URL: {url}")

        # Method 1: Try to extract from goo.gl shortened URL by following redirect
        if 'goo.gl' in url or 'maps.app.goo.gl' in url:
            try:
                headers = {
                    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
                }
                print(f"    📡 Following redirect for: {url}")
                response = requests.get(url, allow_redirects=True, timeout=15, headers=headers)
                final_url = response.url
                print(f"    🔗 Expanded URL: {final_url}")
                return extract_coordinates_from_full_url(final_url)
            except Exception as e:
                print(f"    ❌ Redirect failed: {e}")
                return None, None

        # Method 2: Try to extract from full URL directly
        return extract_coordinates_from_full_url(url)

    except Exception as e:
        print(f"    ❌ Error extracting coordinates from {url}: {e}")
        return None, None

def extract_coordinates_from_full_url(url):
    """
    Extract coordinates from full Google Maps URL
    """
    try:
        print(f"    🔍 Analyzing URL patterns in: {url[:100]}...")

        # Pattern 1: @latitude,longitude,zoom (most common)
        pattern1 = r'@(-?\d+\.?\d*),(-?\d+\.?\d*),\d+\.?\d*z'
        match1 = re.search(pattern1, url)
        if match1:
            lat, lng = float(match1.group(1)), float(match1.group(2))
            print(f"    ✅ Found coordinates with @ pattern: {lat}, {lng}")
            return lat, lng

        # Pattern 2: !3d and !4d (Google's internal format)
        pattern_3d4d = r'!3d(-?\d+\.?\d*)!4d(-?\d+\.?\d*)'
        match_3d4d = re.search(pattern_3d4d, url)
        if match_3d4d:
            lat, lng = float(match_3d4d.group(1)), float(match_3d4d.group(2))
            print(f"    ✅ Found coordinates with !3d!4d pattern: {lat}, {lng}")
            return lat, lng

        # Pattern 3: ll=latitude,longitude
        pattern2 = r'll=(-?\d+\.?\d*),(-?\d+\.?\d*)'
        match2 = re.search(pattern2, url)
        if match2:
            lat, lng = float(match2.group(1)), float(match2.group(2))
            print(f"    ✅ Found coordinates with ll= pattern: {lat}, {lng}")
            return lat, lng

        # Pattern 4: center=latitude,longitude
        pattern3 = r'center=(-?\d+\.?\d*),(-?\d+\.?\d*)'
        match3 = re.search(pattern3, url)
        if match3:
            lat, lng = float(match3.group(1)), float(match3.group(2))
            print(f"    ✅ Found coordinates with center= pattern: {lat}, {lng}")
            return lat, lng

        # Pattern 5: q=latitude,longitude
        pattern4 = r'q=(-?\d+\.?\d*),(-?\d+\.?\d*)'
        match4 = re.search(pattern4, url)
        if match4:
            lat, lng = float(match4.group(1)), float(match4.group(2))
            print(f"    ✅ Found coordinates with q= pattern: {lat}, {lng}")
            return lat, lng

        # Pattern 6: /place/@latitude,longitude
        pattern5 = r'/place/@(-?\d+\.?\d*),(-?\d+\.?\d*)'
        match5 = re.search(pattern5, url)
        if match5:
            lat, lng = float(match5.group(1)), float(match5.group(2))
            print(f"    ✅ Found coordinates with /place/@ pattern: {lat}, {lng}")
            return lat, lng

        # Pattern 7: /search/latitude,+longitude (Google Maps search format)
        pattern6 = r'/search/(-?\d+\.?\d*),\+?(-?\d+\.?\d*)'
        match6 = re.search(pattern6, url)
        if match6:
            lat, lng = float(match6.group(1)), float(match6.group(2))
            print(f"    ✅ Found coordinates with /search/ pattern: {lat}, {lng}")
            return lat, lng

        # Pattern 8: Simple latitude,longitude in URL path (backup)
        pattern7 = r'(-?\d+\.\d+),\+?(-?\d+\.\d+)'
        match7 = re.search(pattern7, url)
        if match7:
            lat, lng = float(match7.group(1)), float(match7.group(2))
            print(f"    ✅ Found coordinates with simple pattern: {lat}, {lng}")
            return lat, lng

        print(f"    ❌ No coordinate patterns found in URL")
        return None, None

    except Exception as e:
        print(f"    ❌ Error parsing URL {url}: {e}")
        return None, None

def create_yandex_maps_url(lat, lng):
    """
    Create Yandex Maps URL from coordinates
    """
    if lat and lng:
        return f"https://yandex.com/maps/?ll={lng},{lat}&z=16&l=map"
    return None

def clean_column_name(col_name):
    """
    Clean column names for valid JSON keys
    """
    if pd.isna(col_name):
        return "unnamed_column"

    # Convert to string and clean
    clean_name = str(col_name).strip()
    clean_name = clean_name.replace('"', '')
    clean_name = clean_name.replace('\n', '_')
    clean_name = clean_name.replace('\r', '_')
    clean_name = clean_name.replace('\t', '_')
    clean_name = clean_name.replace(' ', '_')
    clean_name = re.sub(r'[^\w\s-]', '_', clean_name)
    clean_name = re.sub(r'_+', '_', clean_name)
    clean_name = clean_name.strip('_')

    return clean_name if clean_name else "unnamed_column"

def process_excel_to_json(file_path, output_path='output.json'):
    """
    Main function to process Excel file and convert to JSON with coordinates
    """
    try:
        print("📖 Reading Excel file...")
        # Read Excel file with proper encoding handling
        df = pd.read_excel(file_path, engine='openpyxl')

        print(f"✅ Successfully loaded {len(df)} rows and {len(df.columns)} columns")

        # Clean column names
        print("🧹 Cleaning column names...")
        df.columns = [clean_column_name(col) for col in df.columns]

        # Find the column that contains Google Maps URLs
        maps_column = None
        for col in df.columns:
            if df[col].dtype == 'object':
                sample_values = df[col].dropna().head(5).astype(str)
                if any('maps.app.goo.gl' in str(val) or 'goo.gl' in str(val) for val in sample_values):
                    maps_column = col
                    break

        if maps_column:
            print(f"🗺️  Found maps column: {maps_column}")
            print("📍 Extracting coordinates from Google Maps URLs...")

            # Add new columns for coordinates
            df['latitude'] = None
            df['longitude'] = None
            df['yandex_maps_url'] = None

            total_rows = len(df)
            processed = 0

            for index, row in df.iterrows():
                maps_url = row[maps_column]
                if pd.notna(maps_url) and str(maps_url).strip():
                    lat, lng = extract_coordinates_from_google_maps(str(maps_url))
                    if lat and lng:
                        df.at[index, 'latitude'] = lat
                        df.at[index, 'longitude'] = lng
                        df.at[index, 'yandex_maps_url'] = create_yandex_maps_url(lat, lng)
                        processed += 1
                        print(f"  ✅ Row {index+1}: {lat}, {lng}")
                    else:
                        print(f"  ❌ Row {index+1}: Could not extract coordinates")

                    # Small delay to avoid overwhelming servers
                    time.sleep(0.5)  # Increased delay

                # Progress indicator
                if (index + 1) % 10 == 0:
                    print(f"Progress: {index + 1}/{total_rows} rows processed")

            print(f"📊 Successfully extracted coordinates for {processed} out of {total_rows} rows")
        else:
            print("⚠️  No Google Maps URL column found")

        # Convert DataFrame to JSON
        print("🔄 Converting to JSON...")

        # Replace NaN values with None for better JSON formatting
        df = df.where(pd.notnull(df), None)

        # Convert to dictionary format
        json_data = df.to_dict(orient='records')

        # Save to JSON file with proper encoding
        print(f"💾 Saving to {output_path}...")
        with open(output_path, 'w', encoding='utf-8') as f:
            json.dump(json_data, f, ensure_ascii=False, indent=2)

        print(f"✅ JSON successfully saved to '{output_path}'")
        print(f"📈 Total records: {len(json_data)}")

        # Show sample of the data
        if json_data:
            print("\n📋 Sample record:")
            print(json.dumps(json_data[0], ensure_ascii=False, indent=2))

        return json_data

    except FileNotFoundError:
        print(f"❌ Error: File '{file_path}' not found!")
        return None
    except Exception as e:
        print(f"❌ Error processing file: {str(e)}")
        return None

# Main execution
if __name__ == "__main__":
    # Configuration
    input_file = '431 та умумий.xlsx'  # Change this to your actual file path
    output_file = 'output.json'

    print("🚀 Starting Excel to JSON conversion with coordinate extraction...")
    print(f"Input file: {input_file}")
    print(f"Output file: {output_file}")
    print("-" * 50)

    # Process the file
    result = process_excel_to_json(input_file, output_file)

    if result:
        print("\n🎉 Process completed successfully!")
        print(f"✅ Check '{output_file}' for the results")
    else:
        print("\n❌ Process failed. Please check the error messages above.")
