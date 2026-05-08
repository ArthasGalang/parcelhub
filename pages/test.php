<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
        integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="../css/test.css" rel="stylesheet">
    <title>Test</title>
</head>

<body>
    <div class="container">
        <span class="choose">Choose Gender</span>

        <div class="dropdown">
            <div class="select">
                <span>Select Gender</span>
                <i class="fa fa-chevron-left"></i>
            </div>
            <input type="hidden" name="gender">
            <ul class="dropdown-menu">
                <li id="male">Male</li>
                <li id="female">Female</li>
            </ul>
        </div>
    </div>
</body>

</html>