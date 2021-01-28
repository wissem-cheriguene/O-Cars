<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Car;
use App\Entity\Category;
use App\Entity\City;
use App\Entity\User;
use App\Form\AdminBrandModifType;
use App\Form\AdminCarModifType;
use App\Form\AdminCategoryModifType;
use App\Form\AdminCityModifType;
use App\Form\AdminUserModifType;
use App\Form\CarType;
use App\Entity\Images;
use App\Repository\BrandRepository;
use App\Repository\CarRepository;
use App\Repository\CategoryRepository;
use App\Repository\CityRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="administrateur")
     */
    public function adminIndex(){
        return $this->render('admin/index.html.twig');
    }


    /**
     * @Route("/admin/users", name="admin_users_index")
     * @param UserRepository $UserRepository
     */
    public function listUser(UserRepository $UserRepository)
    {
        $users = $UserRepository->findAll();

        return $this->render('admin/listUsers.html.twig',[
            'users' => $users,

        ]);
    }



    /**
     * @Route("/admin/cars", name="admin_cars_index")
     * @param CarRepository $carRepository
     * @return Response
     */
    public function listcars(CarRepository $carRepository)
    {
        $cars = $carRepository->findAll();

        return $this->render('admin/listCars.html.twig',[
            'cars' => $cars,

        ]);
    }


    /**
     * @Route("/admin/car/modifier/{id}", name="admin_car_edit")
     * @param Car $car
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function editcar(Car $car, Request $request)
    {

        $form =$this->createForm(AdminCarModifType::class, $car);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $car->setUpdatedAt(new \DateTime());

            $em= $this->getDoctrine()->getManager();
            $em->persist($car);
            $em->flush();

            $this->addFlash(
                'success',
                "La car à été modifiée"
            );

            $id = $car->getId();

            return $this->redirectToRoute('admin_car_edit',[
                "id"=> $id
            ]);
        }

        $id = $car->getId();

        return $this->render('admin/carEdit.html.twig',[
            'form' => $form->createView(),
            'car'=>$car,
            "id"=> $id
        ]);
    }


    /**
     * @Route("/admin/car/nouvelle/", name="admin_car_new")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function newcar(Request $request)
    {
        $car = new Car();

        //dd($car);
        $form =$this->createForm(AdmincarModifType::class, $car);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $car->setCreatedAt(new \DateTime());

            $em= $this->getDoctrine()->getManager();
            $em->persist($car);
            $em->flush();

            $this->addFlash(
                'success',
                "La car à été crée, vous pouvez modifier en cas d'erreur"
            );

            $id = $car->getId();

            return $this->redirectToRoute('admin_car_edit',[
                "id"=> $id
            ]);
        }

        $id = $car->getId();

        return $this->render('admin/carNew.html.twig',[
            'form' => $form->createView(),
            'car'=>$car,
            "id"=> $id
        ]);
    }


    /**
     * @Route("/admin/cars/delete/{id}", name="admin_car_delete")
     * @param Car $car
     * @param Request $request
     * @return RedirectResponse
     */
    public function deletecar(Car $car, Request $request)
    {
        $em= $this->getDoctrine()->getManager();
        $em->remove($car);
        $em->flush();

        $this->addFlash(
            'success',
            "La car à été supprimé"
        );

        return $this->redirectToRoute('admin_cars_index');
    }







    /**
     * @Route("/admin/users/modifier/{id}", name="admin_users_edit")
     * @param User $user
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function editUser(User $user, Request $request)
    {

        $form =$this->createForm(AdminUserModifType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $user->setUpdatedAt(new \DateTime());

            $em= $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'success',
                "L'utilisateur à été modifiée"
            );

            $id = $user->getId();

            return $this->redirectToRoute('admin_users_edit',[
                "id"=> $id
            ]);
        }

        $id = $user->getId();

        return $this->render('admin/usersEdit.html.twig',[
            'form' => $form->createView(),
            'user'=>$user,
            "id"=> $id
        ]);
    }

    /**
     * @Route("/admin/Users/delete/{id}", name="admin_users_delete")
     * @param User $user
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteUser(User $user, Request $request)
    {

        if ($user->hasRole('ROLE_ADMIN')) {
            $this->addFlash(
                'danger',
                "Un administrateur ne peut pas etre modifié"
            );

            return $this->redirectToRoute('admin_users_index');
        }

        $em= $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        $this->addFlash(
            'success',
            "L'utilisateur à été supprimé"
        );

        return $this->redirectToRoute('admin_users_index');
    }


    /**
     * @Route("/admin/marques", name="admin_brands_index")
     * @param BrandRepository $brandRepository
     * @return Response
     */
    public function listBrand(BrandRepository $brandRepository)
    {
        $brands = $brandRepository->findAll();

        return $this->render('admin/listBrands.html.twig',[
            'brands' => $brands,

        ]);
    }


    /**
     * @Route("/admin/marque/modifier/{id}", name="admin_brand_edit")
     * @param Brand $brand
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function editBrand(Brand $brand, Request $request)
    {

        $form =$this->createForm(AdminBrandModifType::class, $brand);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $brand->setUpdatedAt(new \DateTime());

            $em= $this->getDoctrine()->getManager();
            $em->persist($brand);
            $em->flush();

            $this->addFlash(
                'success',
                "La marque à été modifiée"
            );

            $id = $brand->getId();

            return $this->redirectToRoute('admin_brand_edit',[
                "id"=> $id
            ]);
        }

        $id = $brand->getId();

        return $this->render('admin/brandEdit.html.twig',[
            'form' => $form->createView(),
            'brand'=>$brand,
            "id"=> $id
        ]);
    }


    /**
     * @Route("/admin/marque/nouvelle/", name="admin_brand_new")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function newBrand(Request $request)
    {
        $brand = new Brand();

        //dd($brand);
        $form =$this->createForm(AdminBrandModifType::class, $brand);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $brand->setCreatedAt(new \DateTime());

            $em= $this->getDoctrine()->getManager();
            $em->persist($brand);
            $em->flush();

            $this->addFlash(
                'success',
                "La marque à été crée, vous pouvez modifier en cas d'erreur"
            );

            $id = $brand->getId();

            return $this->redirectToRoute('admin_brand_edit',[
                "id"=> $id
            ]);
        }

        $id = $brand->getId();

        return $this->render('admin/brandNew.html.twig',[
            'form' => $form->createView(),
            'brand'=>$brand,
            "id"=> $id
        ]);
    }


    /**
     * @Route("/admin/marques/delete/{id}", name="admin_brand_delete")
     * @param Brand $brand
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteBrand(Brand $brand, Request $request)
    {
        $em= $this->getDoctrine()->getManager();
        $em->remove($brand);
        $em->flush();

        $this->addFlash(
            'success',
            "La marque à été supprimé"
        );

        return $this->redirectToRoute('admin_brands_index');
    }



    /**
     * @Route("/admin/categories", name="admin_categories_index")
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function listcategories(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();

        return $this->render('admin/listCategories.html.twig',[
            'categories' => $categories,

        ]);
    }


    /**
     * @Route("/admin/categorie/modifier/{id}", name="admin_category_edit")
     * @param Category $category
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function editcategory(Category $category, Request $request)
    {

        $form =$this->createForm(AdminCategoryModifType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $category->setUpdatedAt(new \DateTime());

            $em= $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash(
                'success',
                "La categorie à été modifiée"
            );

            $id = $category->getId();

            return $this->redirectToRoute('admin_category_edit',[
                "id"=> $id
            ]);
        }

        $id = $category->getId();

        return $this->render('admin/categoryEdit.html.twig',[
            'form' => $form->createView(),
            'category'=>$category,
            "id"=> $id
        ]);
    }


    /**
     * @Route("/admin/categorie/nouvelle/", name="admin_category_new")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function newcategory(Request $request)
    {
        $category = new Category();

        //dd($category);
        $form =$this->createForm(AdmincategoryModifType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $category->setCreatedAt(new \DateTime());

            $em= $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash(
                'success',
                "La categorie à été crée, vous pouvez modifier en cas d'erreur"
            );

            $id = $category->getId();

            return $this->redirectToRoute('admin_category_edit',[
                "id"=> $id
            ]);
        }

        $id = $category->getId();

        return $this->render('admin/categoryNew.html.twig',[
            'form' => $form->createView(),
            'category'=>$category,
            "id"=> $id
        ]);
    }


    /**
     * @Route("/admin/categories/delete/{id}", name="admin_category_delete")
     * @param Category $category
     * @param Request $request
     * @return RedirectResponse
     */
    public function deletecategory(Category $category, Request $request)
    {
        $em= $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        $this->addFlash(
            'success',
            "La categorie à été supprimée"
        );

        return $this->redirectToRoute('admin_categories_index');
    }


    /**
     * @Route("/admin/cities", name="admin_cities_index")
     * @param CityRepository $cityRepository
     * @return Response
     */
    public function listcities(CityRepository $cityRepository)
    {
        $cities = $cityRepository->findAll();

        return $this->render('admin/listCities.html.twig',[
            'cities' => $cities,

        ]);
    }


    /**
     * @Route("/admin/citie/modifier/{id}", name="admin_city_edit")
     * @param City $city
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function editcity(City $city, Request $request)
    {

        $form =$this->createForm(AdminCityModifType::class, $city);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $city->setUpdatedAt(new \DateTime());

            $em= $this->getDoctrine()->getManager();
            $em->persist($city);
            $em->flush();

            $this->addFlash(
                'success',
                "La ville à été modifiée"
            );

            $id = $city->getId();

            return $this->redirectToRoute('admin_city_edit',[
                "id"=> $id
            ]);
        }

        $id = $city->getId();

        return $this->render('admin/cityEdit.html.twig',[
            'form' => $form->createView(),
            'city'=>$city,
            "id"=> $id
        ]);
    }


    /**
     * @Route("/admin/citie/nouvelle/", name="admin_city_new")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function newcity(Request $request)
    {
        $city = new City();

        //dd($city);
        $form =$this->createForm(AdminCityModifType::class, $city);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $city->setCreatedAt(new \DateTime());

            $em= $this->getDoctrine()->getManager();
            $em->persist($city);
            $em->flush();

            $this->addFlash(
                'success',
                "La ville à été crée, vous pouvez modifier en cas d'erreur"
            );

            $id = $city->getId();

            return $this->redirectToRoute('admin_city_edit',[
                "id"=> $id
            ]);
        }

        $id = $city->getId();

        return $this->render('admin/cityNew.html.twig',[
            'form' => $form->createView(),
            'city'=>$city,
            "id"=> $id
        ]);
    }


    /**
     * @Route("/admin/cities/delete/{id}", name="admin_city_delete")
     * @param City $city
     * @param Request $request
     * @return RedirectResponse
     */
    public function deletecity(City $city, Request $request)
    {
        $em= $this->getDoctrine()->getManager();
        $em->remove($city);
        $em->flush();

        $this->addFlash(
            'success',
            "La ville à été supprimée"
        );

        return $this->redirectToRoute('admin_cities_index');
    }


}
