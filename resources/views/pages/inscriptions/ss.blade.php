4 relances par Ligne

@media print {
  body {
    font-family: Arial, sans-serif;
    font-size: 13px;
  }

  .lettres-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    gap: 10px;
  }

  .lettre-relance {
    width: 48%;
    border: 1px solid #000;
    padding: 8px;
    box-sizing: border-box;
    margin-bottom: 10px;
    page-break-inside: avoid;
  }

  .lettre-relance p {
    font-size: 13px;
    margin: 3px 0;
  }

  .lettre-relance table {
    width: 100%;
    border-collapse: collapse;
    font-size: 11px;
    margin-top: 5px;
  }

  .lettre-relance th,
  .lettre-relance td {
    border: 1px solid #000;
    padding: 3px;
    text-align: center;
  }

  h1 {
    font-size: 16px;
    text-align: center;
    margin-bottom: 10px;
  }

  @page {
    size: A4 landscape;
    margin: 10mm;
  }
}